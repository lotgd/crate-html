<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Console\Commands;

use LotGD\Crate\WWW\Model\User;
use Symfony\Component\Console\{Input\InputArgument,
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
class UserDescribeCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:user:describe')
            ->setDescription('Describes a specific user in more Detail.')
            ->setDefinition(new InputDefinition([
                new InputArgument("user_id", InputArgument::REQUIRED, "ID of the user to describe.")
            ]));
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        $user_id = $input->getArgument("user_id");

        /** @var $user User */
        $user = $this->game->getEntityManager()->getRepository(User::class)->find($user_id);

        if (!$user) {
            $style->error("No user found with id {$user_id}.");
            return;
        }

        $style->title("About user {$user->getDisplayName()}");
        $style->listing([
            "ID: {$user->getId()}",
            "Name: {$user->getDisplayName()}",
            "Email: {$user->getEmail()}",
        ]);

        $style->section("Characters");
        $characters = [];
        foreach ($user->getCharacters() as $character) {
            $characters[] = "{$character->getName()} ({$character->getId()}), level {$character->getLevel()}";
        }
        $style->listing($characters);

        $style->section("Roles");
        $style->listing($user->getRoles());
    }
}