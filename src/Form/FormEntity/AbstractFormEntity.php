<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form\FormEntity;


use LotGD\Core\Events\EventContextData;
use LotGD\Core\Game;

/**
 * Class AbstractFormEntity
 */
abstract class AbstractFormEntity
{
    private $game;
    private $variables = [];

    /**
     * AbstractFormEntity constructor.
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if (isset($this->variables[$name])) {
            return $this->variables[$name];
        } else {
            return null;
        }
    }

    /**
     * @param string $name
     * @param $parameter
     * @throws \Exception
     */
    public function __set(string $name, $parameter)
    {
        if (isset($this->variables[$name])) {
            $this->variables[$name] = $parameter;
        } else {
            throw new \Exception("Class " . static::class . " does not have a property $name.");
        }
    }

    /**
     * @param $entity
     */
    public function loadFromEntity($entity)
    {
        $classname = static::class;

        $contextData = EventContextData::create([
            "entity" => $entity,
            "formEntity" => $this,
        ]);

        $this->game->getEventManager()->publish("h/lotgd/crate/html/formEntity/load/{$classname}", $contextData);
    }

    /**
     * @param $entity
     */
    public function saveToEntity($entity)
    {
        $classname = static::class;
        $contextData = EventContextData::create([
            "entity" => $entity,
            "formEntity" => $this,
        ]);

        $this->game->getEventManager()->publish("h/lotgd/crate/html/formEntity/save/{$classname}", $contextData);

        $this->game->getEntityManager()->flush();
    }

    /**
     * @param $var
     * @param $value
     */
    public function set($var, $value)
    {
        $this->variables[$var] = $value;
    }

    /**
     * @param $var
     * @return mixed|null
     */
    public function get($var)
    {
        if (isset($this->variables[$var])) {
            return $this->variables[$var];
        } else {
            return null;
        }
    }
}