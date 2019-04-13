<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\AdministrationToolboxes;


use LotGD\Core\Game;
use LotGD\Crate\WWW\Model\AdminToolbox;
use LotGD\Crate\WWW\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

interface ToolboxInterface
{
    public function __construct(
        ?string $action, ?string $id,
        AbstractController $controller,
        Game $game,
        User $currentUser,
        Request $request
    );

    public function getToolbox(): ?AdminToolbox;
}