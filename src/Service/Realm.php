<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Service;


use LotGD\Crate\WWW\Service\GameService;
use LotGD\Crate\WWW\Model\ModuleMetadata;

class Realm
{
    private $game;

    public function __construct(GameService $gameService)
    {
        $this->game = $gameService->getGame();
    }

    public function getName()
    {
        return "Daenerys Dev";
    }

    public function getVersion()
    {
        return "0.1-dev";
    }

    public function getCore()
    {
        return new ModuleMetadata($this->game->getComposerManager()->getPackageForLibrary("lotgd/core"));
    }

    public function getCrate()
    {
        return new ModuleMetadata($this->game->getComposerManager()->getComposer()->getPackage());
    }
}