<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form;


use LotGD\Core\Events\EventContextData;
use LotGD\Core\Game;
use LotGD\Crate\WWW\Service\GameService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AbstractForm
 * @package LotGD\Crate\WWW\Form
 */
abstract class AbstractForm extends AbstractType
{
    private $game;

    /**
     * AbstractForm constructor.
     * @param GameService $gameService
     */
    public function __construct(GameService $gameService)
    {
        $this->game = $gameService->getGame();
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $classname = static::class;
        $contextData = EventContextData::create([
            "formBuilder" => $builder,
        ]);

        $this->game->getEventManager()->publish("h/lotgd/crate/html/form/build/{$classname}", $contextData);
    }
}