<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\AdministrationToolboxes;


use Doctrine\DBAL\Types\ConversionException;
use LotGD\Core\Game;
use LotGD\Crate\WWW\Model\AdminToolbox;
use LotGD\Crate\WWW\Model\Toolbox\ToolboxTable;
use LotGD\Crate\WWW\Model\Toolbox\ToolboxTableRow;
use LotGD\Crate\WWW\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserToolbox
{
    private $action;
    private $id;
    private $controller;
    private $game;
    private $currentUser;
    private $request;

    public function __construct(
        ?string $action, ?string $id,
        AbstractController $controller,
        Game $game,
        User $currentUser,
        Request $request
    ) {
        $this->action = $action;
        $this->id = $id;
        $this->controller = $controller;
        $this->game = $game;
        $this->currentUser = $currentUser;
        $this->request = $request;
    }

    public function getToolbox(): ?AdminToolbox
    {
        if ($this->action === null) {
            return $this->getListToolbox();
        } elseif ($this->action === "edit") {
            return $this->getEditToolbox();
        } else {
            return null;
        }
    }

    /**
     * @return AdminToolbox
     * @throws \Exception
     */
    protected function getListToolbox(): ?AdminToolbox
    {
        $toolbox = new AdminToolbox("List of all users");

        # Create table
        $table = new ToolboxTable();
        $table->setHead("ID", "Name", "Email");

        # Fill with rows
        /** @var User[] $users */
        $users = $this->game->getEntityManager()->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $row = new ToolboxTableRow(
                $user->getId()->toString(),
                $user->getId()->toString(),
                $user->getDisplayName(),
                $user->getEmail()
            );

            if ($this->currentUser !== $user) {
                $row->setEditable();
                $row->setDeleteable();
            }

            $table->addRow($row);
        }

        $toolbox->setTable($table);

        return $toolbox;
    }

    /**
     * @return AdminToolbox
     */
    protected function getEditToolbox(): ?AdminToolbox
    {
        $toolbox = null;

        try {
            $user = $this->game->getEntityManager()->getRepository(User::class)->find($this->id);

            if (!$user) {
                throw new \Exception("User with id {$this->id} was not found");
            }

            $toolbox = new AdminToolbox("Edit user {$user->getDisplayName()}");

        } catch (ConversionException $e) {
            $toolbox = new AdminToolbox("Error");
            $toolbox->setError("The given ID is invalid.");
        } catch (\Exception $e) {
            $toolbox = new AdminToolbox("Error");
            $toolbox->setError($e->getMessage());
        }

        return $toolbox;
    }
}