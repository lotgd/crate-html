<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form\FormEntity;


use Doctrine\Common\Collections\Collection;
use LotGD\Core\Models\Character;
use LotGD\Crate\WWW\Model\User;

/**
 * CharacterFormEntity - represents a User entity for form management.
 */
class CharacterFormEntity
{
    private $name;
    private $level;

    /**
     * CharacterFormEntity constructor.
     * @param User|null $character
     */
    public function __construct(?Character $character = null)
    {
        if ($character) {
            $this->name = $character->getName();
            $this->level = $character->getLevel();
        }
    }

    /**
     * @param string $email
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level)
    {
        $this->level = $level;
    }

    /**
     * @return int|null
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }
}