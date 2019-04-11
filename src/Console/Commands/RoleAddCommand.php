<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Console\Commands;


use LotGD\Core\Console\Command\BaseCommand;
use LotGD\Crate\WWW\Model\Role;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to add a role.
 * @package LotGD\Crate\WWW\Console\Commands
 */
class RoleAddCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:role:add')
            ->setDescription('Add a new role.')
            ->setDefinition(new InputDefinition([
                new InputArgument("roleId", InputArgument::REQUIRED, "Name of the role")
            ]));
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        $roleId = $input->getArgument("roleId");

        $role = new Role($roleId);

        try {
            $this->game->getEntityManager()->persist($role);
            $this->game->getEntityManager()->flush();
            $style->success("Role {$roleId} was added successfully.");
        } catch (\Exception $e) {
            $style->error($e->getMessage());
        }
    }
}