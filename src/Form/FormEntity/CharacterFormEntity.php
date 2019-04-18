<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form\FormEntity;


use Doctrine\Common\Collections\Collection;
use LotGD\Core\Models\Character;
use LotGD\Crate\WWW\Model\User;

/**
 * CharacterFormEntity - represents a User entity for form management.
 */
class CharacterFormEntity extends AbstractFormEntity
{
    /**
     * @param Character $entity
     */
    public function loadFromEntity($entity)
    {
        $this->set("name", $entity->getName());
        $this->set("level", $entity->getLevel());
        $this->set("health", $entity->getHealth());
        $this->set("maxHealth", $entity->getMaxHealth());

        parent::loadFromEntity($entity);
    }

    /**
     * @param Character $entity
     */
    public function saveToEntity($entity)
    {
        $entity->setName($this->get("name"));
        $entity->setLevel($this->get("level"));
        $entity->setHealth($this->get("health"));
        $entity->setMaxHealth($this->get("maxHealth"));

       parent::saveToEntity($entity);
    }
}