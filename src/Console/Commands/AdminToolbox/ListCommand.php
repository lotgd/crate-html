<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Console\Commands\AdminToolbox;

use LotGD\Core\Console\Command\BaseCommand;
use LotGD\Crate\WWW\Model\AdminToolboxPage;
use LotGD\Crate\WWW\Model\Role;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to list all roles.
 * @package LotGD\Crate\WWW\Console\Commands
 */
class ListCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:adminToolbox:list')
            ->setDescription('Lists all admin toolboxes.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->game->getEntityManager();
        $style = new SymfonyStyle($input, $output);

        /** @var $toolboxes AdminToolboxPage[] */
        $toolboxes = $em->getRepository(AdminToolboxPage::class)->findAll();

        $table_head = ["ID", "Class", "Title", "Roles with access"];
        $table_rows = [];

        foreach ($toolboxes as $toolbox) {
            /** @var Role $roles */
            $roles = [];
            foreach ($toolbox->getRequiredRoles() as $role) {
                $roles[] = $role->getRole();
            }

            $table_rows[] = [
                $toolbox->getId(),
                $toolbox->getClassName(),
                $toolbox->getName(),
                implode("\n", $roles),
            ];
        }

        $style->table($table_head, $table_rows);

        return Command::SUCCESS;
    }
}