<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Service;


use LotGD\Core\Exceptions\LibraryDoesNotExistException;
use LotGD\Crate\WWW\Service\GameService;
use LotGD\Crate\WWW\Model\ModuleMetadata;

class Realm
{
    private $game;
    private $core;
    private $crate;

    public function __construct(GameService $gameService)
    {
        $this->game = $gameService->getGame();

        $this->core = new ModuleMetadata($this->game->getComposerManager()->getPackageForLibrary("lotgd/core"));

        try {
            $this->crate = $this->game->getComposerManager()->getPackageForLibrary("lotgd/crate-html");
        } catch (LibraryDoesNotExistException $e) {
            $this->crate = $this->game->getComposerManager()->getComposer()->getPackage();
        }
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
        return $this->core;
    }

    public function getCrate()
    {
        return $this->crate;
    }
}