<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form\FormEntity;


use Doctrine\Common\Collections\Collection;
use LotGD\Crate\WWW\Model\User;

/**
 * UserFormEntity - represents a User entity for form management.
 */
class UserFormEntity extends AbstractFormEntity
{
    /**
     * @param User $entity
     */
    public function loadFromEntity($entity)
    {
        $this->set("displayName", $entity->getDisplayName());
        $this->set("email", $entity->getEmail());
        $this->set("roles", $entity->getUserRoles()->toArray());

        parent::loadFromEntity($entity);
    }

    /**
     * @param User $entity
     */
    public function saveToEntity($entity)
    {
        $entity->setDisplayName($this->get("displayName"));
        $entity->setEmail($this->get("email"));

        # Remove all roles from user
        $user_roles = $entity->getUserRoles();
        foreach ($user_roles as $role) {
            $entity->removeRole($role);
        }
        # Add in new roles
        $new_roles = $this->get("roles");
        foreach ($new_roles as $role) {
            $entity->addRole($role);
        }

        parent::saveToEntity($entity);
    }
}