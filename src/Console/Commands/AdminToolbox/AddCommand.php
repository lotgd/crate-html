<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Console\Commands\AdminToolbox;

use LotGD\Core\Console\Command\BaseCommand;
use LotGD\Crate\WWW\Model\AdminToolboxPage;
use LotGD\Crate\WWW\Model\Role;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to list all roles.
 * @package LotGD\Crate\WWW\Console\Commands
 */
class AddCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:adminToolbox:add')
            ->setDescription('Registers a new admin toolbox page.')
            ->setDefinition(new InputDefinition([
                new InputArgument(
                    name:"id",
                    mode: InputArgument::REQUIRED,
                    description: "ID of the admin toolbox to identify"
                ),
                new InputArgument(
                    name: "name",
                    mode: InputArgument::REQUIRED,
                    description: "Name",
                    default: null,
                ),
                new InputArgument(
                    name: "class",
                    mode: InputArgument::REQUIRED,
                    description: "New classname",
                    default: null,
                ),
                new InputArgument(
                    name: "roleIds",
                    mode: InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                    description: "New classname",
                    default: null,
                ),
            ]));
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->game->getEntityManager();
        $style = new SymfonyStyle($input, $output);

        # Make sure the ID does not exist yet
        $id = $input->getArgument("id");
        $existing = $em->getRepository(AdminToolboxPage::class)->find($id);
        if ($existing) {
            $style->error("There is already a registered toolbox with the id {$id} (using class {$existing->getClassName()}");

            return Command::FAILURE;
        }

        # Make sure the desired classname exists
        $className = $input->getArgument("class");
        if (!class_exists($className)) {
            $style->error("Class {$className} was not found. Remember to use use \\\\ to separate namespace levels.");

            return Command::FAILURE;
        }

        # Get roles
        $roleIds = $input->getArgument("roleIds");
        /** @var Role[] $roles */
        $roles = [];
        $hasSuperuserRole = false;

        foreach ($roleIds as $roleId) {
            /** @var Role $role */
            $role = $em->getRepository(Role::class)->find($roleId);

            if (!$role) {
                $style->warning("RoleID {$roleId} was not found and was skipped.");
            } else {
                $roles[] = $role;
                if ($role->getRole() === "ROLE_SUPERUSER") {
                    $hasSuperuserRole = true;
                }
            }
        }

        if (!$hasSuperuserRole) {
            /** @var Role $role */
            $role = $em->getRepository(Role::class)->find("ROLE_SUPERUSER");

            if ($role) {
                $style->note("ROLE_SUPERUSER role was automatically added.");

                $roles = [$role, ...$roles];
            } else {
                $style->note("There is no ROLE_SUPERUSER role");
            }
        }

        $newToolbox = new AdminToolboxPage(
            id: $id,
            className: $className,
            name: $input->getArgument("name"),
            requiredRoles: $roles,
        );


        try {
            $em->persist($newToolbox);
            $em->flush();

            $style->success("Admin toolbox page was added.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $style->error("An error occured: $e");
            return Command::FAILURE;
        }
    }
}