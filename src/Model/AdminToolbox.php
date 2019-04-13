<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Model;


use LotGD\Crate\WWW\Model\Toolbox\ToolboxTable;
use Symfony\Component\Form\FormView;

/**
 * AdminToolbox class allows the easy preparation of an admin page, like tables or forms.
 */
class AdminToolbox
{
    private $title;
    private $errorMessage;
    private $successMessage;
    private $table;
    private $form;

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

    /**
     * @param string $message
     */
    public function setSuccessMessage(string $message)
    {
        $this->successMessage = $message;
    }

    /**
     * @return string|null
     */
    public function getSuccessMessage(): ?string
    {
        return $this->successMessage;
    }

    /**
     * @param FormView $formView
     */
    public function setForm(FormView $formView)
    {
        $this->form = $formView;
    }

    /**
     * @return FormView|null
     */
    public function getForm(): ?FormView
    {
        return $this->form;
    }
}