<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\AdministrationToolboxes;


use LotGD\Core\Game;
use LotGD\Crate\WWW\Controller\AdministrationController;
use LotGD\Crate\WWW\Model\User;
use LotGD\Crate\WWW\Twig\AdminToolbox;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractToolbox implements ToolboxInterface
{
    protected $action;
    protected $id;
    protected $controller;
    protected $game;
    protected $currentUser;
    protected $request;

    public function __construct(
        ?string $action, ?string $id,
        AdministrationController $controller,
        Game $game,
        User $currentUser,
        Request $request
    ) {
        $this->action = $action;
        $this->id = $id;
        $this->controller = $controller;
        $this->game = $game;
        $this->currentUser = $currentUser;
        $this->request = $request;
    }

    public function getToolbox(): ?AdminToolbox
    {
        if ($this->action === null) {
            return $this->getListToolbox();
        } elseif ($this->action === "edit") {
            return $this->getEditToolbox();
        } elseif ($this->action === "drop") {
            return $this->getDropToolbox();
        } else {
            return null;
        }
    }

    abstract protected function getListToolbox(): ?AdminToolbox;
    abstract protected function getEditToolbox(): ?AdminToolbox;
    abstract protected function getDropToolbox(): ?AdminToolbox;

    // Helper methods
    protected function createEditToolboxPage(
        string $title,
        string $successMessage,
        string $formClass,
        object $dbEntity,
        string $formEntityClass,
        array $options = [],
    ): AdminToolbox {
        # Create a twig toolbox template
        $toolbox = new AdminToolbox($title);

        # Convert the database entity to a form entity.
        # This makes the entity work better together with the form,
        # and the abstract FormEntity raises events for extension.
        $formEntity = new $formEntityClass($this->game);
        $formEntity->loadFromEntity($dbEntity);

        # Create the form
        $form = $this->controller->createForm($formClass, $formEntity, $options);

        # Make the form aware of the current request - this updates the fields depending on $_POST
        $form->handleRequest($this->request);

        # If submitted and valid, save it.
        if ($form->isSubmitted() and $form->isValid()) {
            $formEntity->saveToEntity($dbEntity);
            $this->controller->addFlash("success", $successMessage);
        }

        # Set the form to the toolbox
        $toolbox->setForm($form->createView());

        return $toolbox;
    }
}