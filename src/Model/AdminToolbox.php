<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Model;


use LotGD\Crate\WWW\Model\Toolbox\ToolboxTable;

/**
 * AdminToolbox class allows the easy preparation of an admin page, like tables or forms.
 */
class AdminToolbox
{
    private $title;
    private $table;
    private $errorMessage;

    /**
     * AdminToolbox constructor.
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * Returns the title of this toolbox.
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Returns a set ToolboxTable or null.
     * @return ToolboxTable|null
     */
    public function getTable(): ?ToolboxTable
    {
        return $this->table;
    }

    /**
     * Sets a ToolboxTable.
     * @param ToolboxTable $table
     */
    public function setTable(ToolboxTable $table)
    {
        $this->table = $table;
    }

    /**
     * Sets an error message.
     * @param string $message
     */
    public function setError(string $message)
    {
        $this->errorMessage = $message;
    }

    /**
     * Returns an error message if one has been set.
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->errorMessage;
    }
}