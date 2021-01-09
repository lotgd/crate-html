<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Console\Commands\User;

use LotGD\Crate\WWW\Model\User;
use Symfony\Component\Console\{Command\Command,
    Input\InputArgument,
    Input\InputDefinition,
    Input\InputOption,
    Input\InputInterface,
    Output\OutputInterface,
    Style\SymfonyStyle};
use LotGD\Core\Console\Command\BaseCommand;

/**
 * Lists all users.
 * @package LotGD\Crate\WWW\Console\Commands
 */
class RemoveCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:user:remove')
            ->setDescription('Removes a users from the database.')
            ->setDefinition(new InputDefinition([
                new InputArgument("user_id", InputArgument::REQUIRED, "ID of the user to remove.")
            ]));
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $user_id = $input->getArgument("user_id");

        /** @var $user User */
        $user = $this->game->getEntityManager()->getRepository(User::class)->find($user_id);

        if (!$user) {
            $style->error("No user found with id {$user_id}.");

            return Command::FAILURE;
        }

        // Remove user.
        $user_name = $user->getDisplayName();
        try {
            $this->game->getEntityManager()->remove($user);
            $this->game->getEntityManager()->flush();
            $style->success("User with id {$user_id} successfully removed ($user_name).");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $style->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}