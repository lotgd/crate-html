<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Console\Commands;


use Doctrine\DBAL\DBALException;
use LotGD\Core\Console\Command\BaseCommand;
use LotGD\Crate\WWW\Model\Role;
use LotGD\Crate\WWW\Model\User;
use Symfony\Component\Console\Command\Command;
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
                new InputArgument("userId", InputArgument::REQUIRED, "ID or name of the user")
            ]));
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $roleId = $input->getArgument("roleId");
        $userId = $input->getArgument("userId");
        $em = $this->game->getEntityManager();

        /** @var Role $role */
        $role = $em->getRepository(Role::class)->find($roleId);
        if (!$role) {
            $style->error("Cannot find role with id {$roleId}");

            return Command::FAILURE;
        }

        /** @var User $user */
        try {
            $user = $em->getRepository(User::class)->find($userId);
        } catch (\Exception $e) {
            $user = $em->getRepository(User::class)->findOneBy(["displayName" => $userId]);
        }


        if (!$user) {
            $user = $em->getRepository(User::class)->findOneBy(["displayName" => $userId]);

            if (!$user) {
                $style->error("Cannot find user with id or name {$userId}");

                return Command::FAILURE;
            }
        }

        try {
            if ($user->getUserRoles()->contains($role)) {
                $style->error("This role was already given to this user.");

                return Command::FAILURE;
            }

            $user->addRole($role);
            $this->game->getEntityManager()->flush();

            $style->success("User {$user->getDisplayName()} was successfully granted the role of {$role->getRole()}.");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $style->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}