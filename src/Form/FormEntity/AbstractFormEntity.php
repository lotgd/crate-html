<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form\FormEntity;


use LotGD\Core\Events\EventContextData;
use LotGD\Core\Game;

/**
 * Class AbstractFormEntity
 */
abstract class AbstractFormEntity implements \ArrayAccess
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

    public function offsetExists($offset)
    {
        return isset($this->variables[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetUnset($offset) {}

    /**
     * Returns the game.
     * @return Game
     */
    protected function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->variables)) {
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
        if (\array_key_exists($name, $this->variables)) {
            $this->variables[$name] = $parameter;
        } else {
            $available = implode(", ", array_keys($this->variables));
            throw new \Exception("Class " . static::class . " does not have a property $name. Available: {$available}");
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
        if (\array_key_Exists($var, $this->variables)) {
            return $this->variables[$var];
        } else {
            return null;
        }
    }
}