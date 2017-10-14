<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of VotesElectCommand
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class VotesElectCommand extends ContainerAwareCommand
{
    protected function configure ()
    {
        $this
            ->setName('app:votes:elect')
            ->setDescription("Elect the featured decks");
    }

    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('app.feature_manager')->electFeatures();
    }
}