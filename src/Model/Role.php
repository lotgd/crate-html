<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use LotGD\Core\Tools\Model\Deletor;
use LotGD\Core\Tools\Model\Saveable;

/**
 * Class Role
 * @Entity
 * @Table(name="roles")
 */
class Role
{
    use Saveable;
    use Deletor;

    /** @Id @Column(type="string", length=50, unique=True) */
    private $role;
    /** @Column(type="string", length=250, nullable=True) */
    private $module;
    /** @Column(type="datetime") */
    private $createdAt;

    public function __construct(string $role, string $module = null)
    {
        $this->role = $role;
        $this->module = $module;
        $this->createdAt = new \DateTime();
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}