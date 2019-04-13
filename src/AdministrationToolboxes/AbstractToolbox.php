<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\AdministrationToolboxes;


use LotGD\Core\Game;
use LotGD\Crate\WWW\Model\AdminToolbox;
use LotGD\Crate\WWW\Model\User;
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
        AbstractController $controller,
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
}