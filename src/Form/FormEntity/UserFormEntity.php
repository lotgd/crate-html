<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form\FormEntity;


use Doctrine\Common\Collections\Collection;
use LotGD\Crate\WWW\Model\User;

/**
 * UserFormEntity - represents a User entity for form management.
 */
class UserFormEntity
{
    private $displayName;
    private $email;
    private $roles;

    /**
     * UserFormEntity constructor.
     * @param User|null $user
     */
    public function __construct(?User $user = null)
    {
        if ($user) {
            $this->displayName = $user->getDisplayName();
            $this->email = $user->getEmail();
            $this->roles = $user->getUserRoles()->toArray();
        }
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName(string $displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }
}