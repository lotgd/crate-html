<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use LotGD\Core\Models\Actor;
use LotGD\Core\Models\SaveableInterface;
use LotGD\Core\Tools\Model\Saveable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package LotGD\Crate\WWW\Model
 * @Entity
 * @Table(name="users")
 */
class User implements SaveableInterface, UserInterface
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

    public function getUsername()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->passwordHash;
    }

    public function getSalt() {}

    public function eraseCredentials()
    {
    }

    public function getRoles()
    {
        return ["ROLE_USER"];
    }
}