<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Model\Toolbox;


class ToolboxTableRow
{
    private $id;
    private $cols = [];
    private $editable = false;
    private $deleteable = false;

    /**
     * ToolboxTableRow constructor.
     * @param string $id
     * @param string ...$cols
     */
    public function __construct(string $id, string ...$cols)
    {
        $this->id = $id;
        $this->cols = $cols;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array|string[]
     */
    public function getCols(): array
    {
        return $this->cols;
    }

    /**
     * Returns the number of columns from this row.
     * @return int
     */
    public function getColumnLength(): int
    {
        return count($this->cols);
    }

    /**
     * Returns true if this row has any options.
     * @return bool
     */
    public function hasOptions(): bool
    {
        return ($this->editable or $this->deleteable);
    }

    /**
     * Allows this row to be edited.
     */
    public function setEditable()
    {
        $this->editable = true;
    }

    /**
     * Returns true if this row is editable.
     * @return bool
     */
    public function isEditable(): bool
    {
        return $this->editable;
    }

    /**
     * Allows this row to be deleted.
     */
    public function setDeleteable()
    {
        $this->deleteable = true;
    }

    /**
     * Returns true if this row can be deleted.
     * @return bool
     */
    public function isDeleteable(): bool
    {
        return $this->deleteable;
    }
}