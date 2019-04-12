<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Model\Toolbox;


class ToolboxTable
{
    private $head = [];
    private $rows = [];
    private $options = false;

    public function __construct()
    {
    }

    public function setHead(string ... $head)
    {
        $this->head = $head;
    }

    /**
     * @return array|string[]
     */
    public function getHead(): array
    {
        return $this->head;
    }

    public function getColumnLength(): int
    {
        return count($this->head);
    }

    /**
     * @return bool
     */
    public function hasOptions(): bool
    {
        return $this->options;
    }

    /**
     * @param ToolboxTableRow $row
     * @throws \Exception
     */
    public function addRow(ToolboxTableRow $row)
    {
        if ($row->getColumnLength() !== $this->getColumnLength()) {
            throw new \Exception("Number of row columns must be the same as number of head columns.");
        }

        if ($row->hasOptions()) {
            $this->options = true;
        }

        $this->rows[] = $row;
    }

    /**
     * @return array|ToolboxTableRow[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }
}