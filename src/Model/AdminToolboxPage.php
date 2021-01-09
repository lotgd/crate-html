<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Ramsey\Uuid\Uuid;

/**
 * Class Role
 * @Entity
 * @Table(name="admin_toolbox_pages")
 */
class AdminToolboxPage
{
    /** @Id @Column(type="string", length=255, unique=True) */
    private string $id;
    /** @Column(type="string", length=255) */
    private string $className;
    /**
     * @var Collection
     * @ManyToMany(targetEntity="LotGD\Crate\WWW\Model\Role", fetch="EAGER")
     * @JoinTable("admin_toolbox_pages_required_roles",
     *     joinColumns={@JoinColumn(name="toolboxpage_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="role", referencedColumnName="role")}
     * )
     */
    private Collection $requiredRoles;
    /** @Column(type="string", length=255, options={"default"=""}) */
    private string $name;

    /**
     * AdminToolboxPage constructor.
     * @param string $id
     * @param string $className
     * @param string $name
     * @param array $requiredRoles
     */
    public function __construct(string $id, string $className, string $name, array $requiredRoles = [])
    {
        $this->id = $id;
        $this->className = $className;
        $this->name = $name;
        $this->requiredRoles = new ArrayCollection();

        foreach ($requiredRoles as $role) {
            $this->requiredRoles->add($role);
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className) {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection
     */
    public function getRequiredRoles(): Collection
    {
        return $this->requiredRoles;
    }

    /**
     * @param Role $role
     */
    public function addRequiredRole(Role $role)
    {
        $this->requiredRoles->add($role);
    }

    /**
     * @param Role $role
     */
    public function removeRequiredRole(Role $role)
    {
        $this->requiredRoles->removeElement($role);
    }
}