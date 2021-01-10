<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\AdministrationToolboxes;

use Doctrine\DBAL\Types\ConversionException;
use LotGD\Crate\WWW\Form\FormEntity\SceneFormEntity;
use LotGD\Crate\WWW\Form\FormEntity\UserFormEntity;
use LotGD\Crate\WWW\Form\SceneEditForm;
use LotGD\Crate\WWW\Model\Role;
use LotGD\Crate\WWW\Model\User;
use LotGD\Crate\WWW\Twig\AdminToolbox;
use LotGD\Crate\WWW\Twig\Toolbox\ToolboxTable;
use LotGD\Crate\WWW\Twig\Toolbox\ToolboxTableRow;
use LotGD\Core\Models\Scene;
use LotGD\Core\Models\SceneTemplate;

class SceneToolbox extends AbstractToolbox
{
    /**
     * @return AdminToolbox
     * @throws \Exception
     */
    protected function getListToolbox(): ?AdminToolbox
    {
        $toolbox = new AdminToolbox("List of all scenes");

        # Create table
        $table = new ToolboxTable();
        $table->setHead("ID", "Name", "Email");

        # Fill with rows
        /** @var Scene[] $scenes */
        $scenes = $this->game->getEntityManager()->getRepository(Scene::class)->findAll();
        foreach ($scenes as $scene) {
            $row = new ToolboxTableRow(
                $scene->getId(),
                $scene->getId(),
                $scene->getTitle(),
                substr($scene->getDescription(), 0, 50) . "...",
            );

            $row->setEditable();

            if ($this->currentUser !== $scene) {
                $row->setDeleteable();
            }

            $table->addRow($row);
        }

        $toolbox->setTable($table);

        return $toolbox;
    }

    /**
     * @return AdminToolbox
     */
    protected function getEditToolbox(): ?AdminToolbox
    {
        $toolbox = null;
        $em = $this->game->getEntityManager();

        try {
            /** @var Scene $scene */
            $scene = $em->getRepository(Scene::class)->find($this->id);

            if (!$scene) {
                throw new \Exception("Scene with id {$this->id} was not found");
            }

            $toolbox = $this->createEditToolboxPage(
                title: "Edit scene {$scene->getTitle()}",
                successMessage: "Scene edited successfully",
                formClass: SceneEditForm::class,
                dbEntity: $scene,
                formEntityClass: SceneFormEntity::class,
                options: [
                    "templates" => $em->getRepository(SceneTemplate::class)->findBy(["userAssignable" => true])
                ]
            );
        } catch (ConversionException) {
            $toolbox = new AdminToolbox("Error");
            $toolbox->setError("The given ID is invalid.");
        } catch (\Exception $e) {
            $toolbox = new AdminToolbox("Error");
            $toolbox->setError($e->getMessage());
        }

        return $toolbox;
    }

    /**
     * @return AdminToolbox|null
     */
    protected function getDropToolbox(): ?AdminToolbox
    {
        $em = $this->game->getEntityManager();
        $toolbox = null;

        try {
            /** @var User $user */
            $user = $em->getRepository(User::class)->find($this->id);

            if (!$user) {
                throw new \Exception("User with id {$this->id} was not found");
            }

            if ($this->currentUser === $user) {
                throw new \Exception("You cannot delete yourself.g");
            }

            $toolbox = new AdminToolbox("Delete user {$user->getDisplayName()}");

            $em->remove($user);
            $em->flush();

            $toolbox->setSuccessMessage("User was successfully deleted.");
        } catch (ConversionException $e) {
            $toolbox = new AdminToolbox("Error");
            $toolbox->setError("The given ID is invalid.");
        } catch (\Exception $e) {
            $toolbox = new AdminToolbox("Error");
            $toolbox->setError($e->getMessage());
        }

        return $toolbox;
    }
}