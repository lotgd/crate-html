<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\AdministrationToolboxes;

use Doctrine\DBAL\Types\ConversionException;
use LotGD\Crate\WWW\Form\FormEntity\UserFormEntity;
use LotGD\Crate\WWW\Form\UserEditForm;
use LotGD\Crate\WWW\Model\Role;
use LotGD\Crate\WWW\Model\User;
use LotGD\Crate\WWW\Twig\AdminToolbox;
use LotGD\Crate\WWW\Twig\Toolbox\ToolboxTable;
use LotGD\Crate\WWW\Twig\Toolbox\ToolboxTableRow;

class UserToolbox extends AbstractToolbox
{
    /**
     * @return AdminToolbox
     * @throws \Exception
     */
    protected function getListToolbox(): ?AdminToolbox
    {
        $toolbox = new AdminToolbox("List of all users");

        # Create table
        $table = new ToolboxTable();
        $table->setHead("ID", "Name", "Email");

        # Fill with rows
        /** @var User[] $users */
        $users = $this->game->getEntityManager()->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $row = new ToolboxTableRow(
                $user->getId()->toString(),
                $user->getId()->toString(),
                $user->getDisplayName(),
                $user->getEmail()
            );

            $row->setEditable();

            if ($this->currentUser !== $user) {
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
            /** @var User $user */
            $user = $em->getRepository(User::class)->find($this->id);

            if (!$user) {
                throw new \Exception("User with id {$this->id} was not found");
            }

            $toolbox = new AdminToolbox("Edit user {$user->getDisplayName()}");

            $userFormEntity = new UserFormEntity($this->game);
            $userFormEntity->loadFromEntity($user);

            $form = $this->controller->createForm(UserEditForm::class, $userFormEntity, [
                "roles" => $em->getRepository(Role::class)->findAll()
            ]);
            $form->handleRequest($this->request);

            if ($form->isSubmitted() and $form->isValid()) {
                $userFormEntity->saveToEntity($user);
                $this->controller->addFlash('success', 'User edited successfully.');
            }

            $toolbox->setForm($form->createView());
        } catch (ConversionException $e) {
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