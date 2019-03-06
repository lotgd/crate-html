<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Service;

use Doctrine\ORM\EntityManagerInterface;
use LotGD\Core\Bootstrap;
use LotGD\Core\Game;


class GameService
{
    protected static $game;

    public function __construct()
    {
        if (empty(self::$game)) {
            if (basename(getcwd()) === "public") {
                $game = Bootstrap::createGame(getcwd() . DIRECTORY_SEPARATOR . "..");
            } else {
                $game = Bootstrap::createGame(getcwd());
            }

            self::$game = $game;
        }
    }

    public function getGame(): Game
    {
        return self::$game;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return self::$game->getEntityManager();
    }
}