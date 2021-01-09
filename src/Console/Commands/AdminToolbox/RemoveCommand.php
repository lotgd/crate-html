<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Console\Commands\AdminToolbox;

use LotGD\Core\Console\Command\BaseCommand;
use LotGD\Crate\WWW\Model\AdminToolboxPage;
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
class RemoveCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('crate:adminToolbox:remove')
            ->setDescription('Removes an admin toolboxes.')
            ->setDefinition(new InputDefinition([
                new InputArgument(
                    name:"id",
                    mode: InputArgument::REQUIRED,
                    description: "ID of the admin toolbox to edit."
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

        $toolbox_id = $input->getArgument("id");

        $toolbox = $em->getRepository(AdminToolboxPage::class)->find($toolbox_id);

        if (!$toolbox) {
            $style->error("No admin toolbox found with id {$toolbox_id}.");
            return Command::FAILURE;
        }

        try {
            $em->remove($toolbox);$em->flush();

            $style->success("Entry {$toolbox_id} was successfully removed.");
        } catch (\Exception $e) {
            $style->error("An error occured: {$e}.");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}