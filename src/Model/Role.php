<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Model;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
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
    private string $role;
    /** @Column(type="string", length=250, nullable=True) */
    private ?string $module;
    /** @Column(type="datetime") */
    private DateTime $createdAt;

    /**
     * Role constructor.
     * @param string $role
     * @param string|null $module
     * @throws \Exception
     */
    public function __construct(string $role, string $module = null)
    {
        $this->role = $role;
        $this->module = $module;
        $this->createdAt = new DateTime();
    }

    /**
     * Returns the role id / name
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Returns the module
     * @return string|null
     */
    public function getModule(): ?string
    {
        return $this->module;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}