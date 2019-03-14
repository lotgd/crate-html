<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Console\Commands;

use LotGD\Crate\WWW\Model\User;
use Symfony\Component\Console\ {
    Input\InputDefinition,
    Input\InputOption,
    Input\InputInterface,
    Output\OutputInterface
};
use LotGD\Core\Console\Command\BaseCommand;

class ListUsersCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:user:list')
            ->setDescription('Lists all user.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $users User[] */
        $users = $this->game->getEntityManager()->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $output->write(sprintf(
                "%s %20s %30s\n",
                $user->getId(),
                $user->getDisplayName(),
                $user->getEmail()
            ));
        }

        $output->write("\n\n");
    }
}