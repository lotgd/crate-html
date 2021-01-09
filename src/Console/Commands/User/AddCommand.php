<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Console\Commands\User;

use Symfony\Component\Console\{Command\Command,
    Input\InputArgument,
    Input\InputDefinition,
    Input\InputOption,
    Input\InputInterface,
    Output\OutputInterface,
    Style\SymfonyStyle};
use LotGD\Core\Console\Command\BaseCommand;
use LotGD\Crate\WWW\Manager\UserManager;
use LotGD\Crate\WWW\Model\User;

/**
 * Command to create a password user.
 */
class AddCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:user:add')
            ->setDescription('Adds a user.')
            ->setDefinition(
               new InputDefinition(array(
                   new InputArgument('username',InputArgument::REQUIRED, "Username"),
                   new InputArgument('email',InputArgument::REQUIRED, "Email address (must be valid)"),
                   new InputArgument('password',InputArgument::REQUIRED, "Password in plaintext (will get hashed in database)"),
               ))
           );
    }
    
    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $name = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        
        if ($name === null || $email === null || $password === null) {
            $style->error("Name, email and password are not allowed to be null.");

            return Command::FAILURE;
        }

        try {
            $userManager = new UserManager($this->game);
            $user = $userManager->createNewWithPassword($name, $email, $password);
            $this->game->getEntityManager()->flush();

            $style->success("User created with id {$user->getId()}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $style->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
