<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Service;


use LotGD\Crate\WWW\Manager\UserManager;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserService
 * @package LotGD\Crate\WWW\Service
 * @see UserManager
 */
class UserService
{
    private $userManager;

    public function __construct(GameService $gameService)
    {
        $this->userManager = new UserManager($gameService->getGame());
    }

    public function __call($method, $arguments)
    {
        return $this->userManager->$method(...$arguments);
    }
}