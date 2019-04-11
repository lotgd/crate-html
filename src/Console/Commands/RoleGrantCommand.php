<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Console\Commands;


use LotGD\Core\Console\Command\BaseCommand;
use LotGD\Crate\WWW\Model\Role;
use LotGD\Crate\WWW\Model\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to add a role to a user.
 * @package LotGD\Crate\WWW\Console\Commands
 */
class RoleGrantCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:role:grant')
            ->setDescription('Grants role to a given user.')
            ->setDefinition(new InputDefinition([
                new InputArgument("roleId", InputArgument::REQUIRED, "Name of the role"),
                new InputArgument("userId", InputArgument::REQUIRED, "ID of the user")
            ]));
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        $roleId = $input->getArgument("roleId");
        $userId = $input->getArgument("userId");
        $em = $this->game->getEntityManager();

        /** @var Role $role */
        $role = $em->getRepository(Role::class)->find($roleId);
        if (!$role) {
            $style->error("Cannot find role with id {$roleId}");
            return;
        }

        /** @var User $user */
        $user = $em->getRepository(User::class)->find($userId);
        if (!$user) {
            $style->error("Cannot find user with id {$userId}");
            return;
        }

        try {
            $user->grantRole($role);
            $this->game->getEntityManager()->flush();

            $style->success("User {$user->getDisplayName()} was successfully granted the role of {$role->getRole()}.");
        } catch (\Exception $e) {
            $style->error($e->getMessage());
        }
    }
}