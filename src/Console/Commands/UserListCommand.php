<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Console\Commands;

use LotGD\Crate\WWW\Model\User;
use Symfony\Component\Console\{Input\InputDefinition,
    Input\InputOption,
    Input\InputInterface,
    Output\OutputInterface,
    Style\SymfonyStyle};
use LotGD\Core\Console\Command\BaseCommand;

/**
 * Lists all users.
 * @package LotGD\Crate\WWW\Console\Commands
 */
class UserListCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:user:list')
            ->setDescription('Lists all users.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        /** @var $users User[] */
        $users = $this->game->getEntityManager()->getRepository(User::class)->findAll();

        $table_head = ["ID", "Name", "Email"];
        $table_rows = [];

        foreach ($users as $user) {
            $table_rows[] = [
                $user->getId(),
                $user->getDisplayName(),
                $user->getEmail()
            ];
        }

        $style->table($table_head, $table_rows);
    }
}