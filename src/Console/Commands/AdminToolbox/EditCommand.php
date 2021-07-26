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
class EditCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:adminToolbox:edit')
            ->setDescription('Lists all admin toolboxes.')
            ->setDefinition(new InputDefinition([
                new InputArgument(
                    name:"id",
                    mode: InputArgument::REQUIRED,
                    description: "ID of the admin toolbox to edit."
                ),
                new InputOption(
                    name: "class",
                    mode: InputArgument::OPTIONAL,
                    description: "New classname",
                    default: null,
                ),
                new InputOption(
                    name: "name",
                    mode: InputArgument::OPTIONAL,
                    description: "New toolbox name",
                    default: null,
                ),
                new InputOption(
                    name: "addRoles",
                    mode: InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                    description: "Add role identifiers required to access this page",
                    default: null,
                ),
                new InputOption(
                    name: "removeRoles",
                    mode: InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                    description: "Remove role identifiers required to access this page",
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
        $changed = false;

        $toolbox_id = $input->getArgument("id");

        /** @var AdminToolboxPage $toolbox */
        $toolbox = $em->getRepository(AdminToolboxPage::class)->find($toolbox_id);

        if (!$toolbox) {
            $style->error("No admin toolbox found with id {$toolbox_id}.");
            return Command::FAILURE;
        }

        # Change name
        $new_name = $input->getOption("name");
        if ($new_name) {
            $toolbox->setName($new_name);
            $changed = true;
        }

        # Change class
        $new_class = $input->getOption("class");
        if ($new_class) {
            if (!class_exists($new_class)) {
                $style->error("Class {$new_class} has not been found. ");
                return Command::FAILURE;
            }

            $toolbox->setClassName($new_class);
            $changed = true;
        }

        $roleRepository = $em->getRepository(Role::class);

        # Add roles
        $rolesToAdd = $input->getOption("addRoles");

        foreach ($rolesToAdd as $roleId) {
            /** @var Role $role */
            $role = $roleRepository->find($roleId);

            if (!$role) {
                $style->warning("RoleID {$roleId} was not found and was skipped.");
            } elseif ($toolbox->getRequiredRoles()->contains($role)) {
                $style->note("{$roleId} already exists and was skipped");
            } else {
                $toolbox->addRequiredRole($role);
                $changed = true;
                $style->note("RoleID {$roleId} was added.");
            }
        }

        # Remove roles
        $rolesToRemove = $input->getOption("removeRoles");

        foreach ($rolesToRemove as $roleId) {
            if ($roleId === "ROLE_SUPERUSER") {
                $style->error("Cannot remove {$roleId}.");
            }

            /** @var Role $role */
            $role = $roleRepository->find($roleId);

            if (!$role) {
                $style->warning("RoleID {$roleId} was not found and was skipped.");
            } elseif (!$toolbox->getRequiredRoles()->contains($role)) {
                $style->note("Toolbox page does not require {$roleId}, removal was skipped");
            } else {
                $role = $toolbox->getRequiredRoles()->removeElement($role);
                $changed = true;
                $style->note("RoleID {$roleId} was removed.");
            }
        }

        if (!$changed) {
            $style->warning("Nothing has been changed.");
        } else {
            $em->flush();

            $style->success("Entry was successfully changed.");
        }

        return Command::SUCCESS;
    }
}