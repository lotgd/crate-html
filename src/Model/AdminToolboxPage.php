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

/**
 * Class Role
 * @Entity
 * @Table(name="admin_toolbox_pages")
 */
class AdminToolboxPage
{
    /** @Id @Column(type="string", length=255, unique=True) */
    private $id;
    /** @Column(type="string", length=255) */
    private $className;
    /**
     * @var Collection
     * @ManyToMany(targetEntity="LotGD\Crate\WWW\Model\Role", fetch="EAGER")
     * @JoinTable("admin_toolbox_pages_required_roles",
     *     joinColumns={@JoinColumn(name="toolboxpage_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="role", referencedColumnName="role")}
     * )
     */
    private $requiredRoles;

    /**
     * AdminToolboxPage constructor.
     * @param string $id
     * @param string $className
     */
    public function __construct(string $id, string $className)
    {
        $this->id = $id;
        $this->className = $className;
        $this->requiredRoles = new ArrayCollection();
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