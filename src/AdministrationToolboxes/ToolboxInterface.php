<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\AdministrationToolboxes;

use LotGD\Core\Game;
use LotGD\Crate\WWW\Controller\AdministrationController;
use LotGD\Crate\WWW\Model\User;
use LotGD\Crate\WWW\Twig\AdminToolbox;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

interface ToolboxInterface
{
    public function __construct(
        ?string $action,
        ?string $id,
        AdministrationController $controller,
        Game $game,
        User $currentUser,
        Request $request
    );

    public function getToolbox(): ?AdminToolbox;
}