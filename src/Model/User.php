<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use LotGD\Core\Models\Actor;
use LotGD\Core\Models\Character;
use LotGD\Core\Models\SaveableInterface;
use LotGD\Core\Tools\Model\Saveable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @Entity
 * @Table(name="users")
 */
class User implements SaveableInterface, UserInterface, EquatableInterface, \Serializable
{
    use Saveable;

    /** @Id @Column(type="uuid", unique=True) */
    private $id;
    /** @Column(type="string", length=250, unique=True) */
    private $email;
    /** @Column(type="string", length=250, unique=True) */
    private $displayName;
    /** @Column(type="string", length=250); */
    private $passwordHash;
    /**
     * Unidirectional OneToMany association since we cannot modify the character
     * model from the core. Instead, we use a join table to list all characters
     * associated to an user.
     * @ManyToMany(targetEntity="LotGD\Core\Models\Character", cascade={"persist"})
     * @JoinTable("users_characters",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@JoinColumn(name="character_id", referencedColumnName="id", unique=true)}
     * )
     */
    private $characters;
    /**
     * @var Collection
     * @ManyToMany(targetEntity="LotGD\Crate\WWW\Model\Role", fetch="EAGER")
     * @JoinTable("users_roles",
     *     joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="role", referencedColumnName="role")}
     * )
     */
    private $user_roles;

    public function __construct(string $displayName = "", string $email = "", string $password = "")
    {
        $this->id = Uuid::uuid4();
        $this->displayName = $displayName;
        $this->email = $email;
        $this->setPassword($password);
        $this->characters = new ArrayCollection();
        $this->user_roles = new ArrayCollection();
    }

    /** @see \Serializable::serialize() */
    public function serialize(): ?string
    {
        return serialize([$this->id, $this->email, $this->passwordHash]);
    }

    /**
     * @param $serialized
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        [$this->id, $this->email, $this->passwordHash] = unserialize($serialized);
    }

    /** @see EquatableInterface::isEqualTo() */
    public function isEqualTo(UserInterface $user): bool
    {
        if ($user->getId()->toString() === $this->getId()->toString() and
            $user->getUsername() === $this->getUsername() and
            $user->getPassword() === $this->getPassword()
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $name
     */
    public function setDisplayName(string $name): void
    {
        $this->displayName = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    /**
     * Sets a given plaintext password and stores it hash in the database.
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Returns true if the given password matches the stored hash.
     * @param string $password
     * @return bool
     */
    public function passwordIsValid(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    /**
     * Iterates through all characters.
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    /**
     * Returns the character with a given id or null if not found.
     * @param string $characterId
     * @return Character|null
     */
    public function getCharacterWithId(string $characterId): ?Character
    {
        foreach ($this->characters as $character) {
            if ($characterId == $character->getId()) {
                return $character;
            }
        }

        return null;
    }

    /**
     * Returns true if the user has the passed character.
     * @param Character $character
     * @return bool
     */
    public function hasCharacter(Character $character): bool
    {
        if ($character === null) {
            return false;
        }

        return $this->characters->contains($character);
    }

    /**
     * Returns true if the user has the character given by its id
     * @param string $characterId
     * @return bool
     */
    public function hasCharacterWithId(string $characterId): bool
    {
        foreach ($this->characters as $character) {
            if ($characterId == $character->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Character $character
     */
    public function removeCharacter(Character $character)
    {
        $this->characters->removeElement($character);
    }

    /**
     * Adds a character to this user.
     * @param Character $character
     */
    public function addCharacter(Character $character)
    {
        if ($this->hasCharacter($character) === false) {
            $this->characters->add($character);
        }
    }

    /**
     * Symfony "User name" identifier. Here, is is the email address
     * @return string
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * For compability with the symfony UserInterface only.
     * @return null|string|void
     */
    public function getSalt() {}

    /**
     *
     */
    public function eraseCredentials() {}

    /**
     * Returns a list of roles given to the user.
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = ["ROLE_USER"];
        foreach ($this->user_roles as $role) {
            $roles[] = $role->getRole();
        }

        return $roles;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->user_roles;
    }

    /**
     * Grants this user the given role.
     * @param Role $role
     */
    public function addRole(Role $role): void
    {
        $this->user_roles->add($role);
    }

    /**
     * Removes a role from a user.
     * @param Role $role
     */
    public function removeRole(Role $role): void
    {
        $this->user_roles->removeElement($role);
    }

    public function equals(UserInterface $user): bool
    {
        // TODO: Implement equals() method.
    }
}