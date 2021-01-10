<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Form\FormEntity;

use LotGD\Core\Models\Scene;

class SceneFormEntity extends AbstractFormEntity
{
    /**
     * @param Scene $entity
     */
    public function loadFromEntity($entity)
    {
        $this->set("title", $entity->getTitle());
        $this->set("description", $entity->getDescription());
        $this->set("template", $entity->getTemplate());

        parent::loadFromEntity($entity);
    }

    /**
     * @param Scene $entity
     */
    public function saveToEntity($entity)
    {
        $entity->setTitle($this->get("title"));
        $entity->setDescription($this->get("description"));
        $entity->setTemplate($this->get("template"));

        parent::saveToEntity($entity);
    }
}