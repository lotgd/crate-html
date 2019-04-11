<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Console\Commands;


use LotGD\Core\Console\Command\BaseCommand;
use LotGD\Crate\WWW\Model\Role;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to list all roles.
 * @package LotGD\Crate\WWW\Console\Commands
 */
class RoleListCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:role:list')
            ->setDescription('Lists all roles.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        /** @var $roles Role[] */
        $roles = $this->game->getEntityManager()->getRepository(Role::class)->findAll();

        $table_head = ["RoleID", "Module", "Added at"];
        $table_rows = [];

        foreach ($roles as $role) {
            $table_rows[] = [
                $role->getRole(),
                $role->getModule()??"-",
                $role->getCreatedAt()->format(DATE_ISO8601)
            ];
        }

        $style->table($table_head, $table_rows);
    }
}