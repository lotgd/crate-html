<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\AdministrationToolboxes;


use Doctrine\DBAL\Types\ConversionException;
use LotGD\Core\Models\Character;
use LotGD\Crate\WWW\Form\CharacterEditForm;
use LotGD\Crate\WWW\Form\FormEntity\CharacterFormEntity;
use LotGD\Crate\WWW\Twig\AdminToolbox;
use LotGD\Crate\WWW\Twig\Toolbox\ToolboxTable;
use LotGD\Crate\WWW\Twig\Toolbox\ToolboxTableRow;

class CharacterToolbox extends AbstractToolbox
{
    /**
     * @return AdminToolbox
     * @throws \Exception
     */
    protected function getListToolbox(): ?AdminToolbox
    {
        $toolbox = new AdminToolbox("List of all characters");

        # Create table
        $table = new ToolboxTable();
        $table->setHead("ID", "Name", "DisplayName", "Level");

        # Fill with rows
        /** @var Character[] $characters */
        $characters = $this->game->getEntityManager()->getRepository(Character::class)->findAll();
        foreach ($characters as $character) {
            $row = new ToolboxTableRow(
                $character->getId()->toString(),
                $character->getId()->toString(),
                $character->getName(),
                $character->getDisplayName(),
                $character->getLevel()
            );

            $row->setEditable();
            $row->setDeleteable();

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
            /** @var Character $character */
            $character = $em->getRepository(Character::class)->find($this->id);

            if (!$character) {
                throw new \Exception("Character with id {$this->id} was not found");
            }

            $toolbox = new AdminToolbox("Edit character {$character->getName()}");

            $characterFormEntity = new CharacterFormEntity($this->game);
            $characterFormEntity->loadFromEntity($character);

            $form = $this->controller->createForm(CharacterEditForm::class, $characterFormEntity, [
            ]);
            $form->handleRequest($this->request);

            if ($form->isSubmitted() and $form->isValid()) {
                $characterFormEntity->saveToEntity($character);
                $this->controller->addFlash('success', 'Character edited successfully.');
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
            /** @var Character $character */
            $character = $em->getRepository(Character::class)->find($this->id);

            if (!$character) {
                throw new \Exception("Character with id {$this->id} was not found");
            }

            $toolbox = new AdminToolbox("Delete character {$character->getName()}");

            $em->remove($character);
            $em->flush();

            $toolbox->setSuccessMessage("Character was successfully deleted.");
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