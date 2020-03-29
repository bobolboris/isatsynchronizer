<?php

namespace App\Command;

use App\Service\Synchronizer\BackToFront\CustomerSynchronize as CustomerBackToFrontSynchronize;
use App\Service\Synchronizer\FrontToBack\CustomerSynchronize as CustomerFrontToBackSynchronize;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomerSynchronizeCommand extends OneSynchronizeCommand
{
    protected static $defaultName = 'customer:synchronize';
    private $customerBackToFrontSynchronize;
    private $customerFrontToBackSynchronize;

    public function __construct(CustomerBackToFrontSynchronize $customerBackToFrontSynchronize,
                                CustomerFrontToBackSynchronize $customerFrontToBackSynchronize)
    {
        $this->customerBackToFrontSynchronize = $customerBackToFrontSynchronize;
        $this->customerFrontToBackSynchronize = $customerFrontToBackSynchronize;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Synchronize customers');
        $this->addArgument('direction', InputArgument::REQUIRED, 'Direction');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $direction = $input->getArgument('direction');

        if ('frontToBack' === $direction) {
            $this->customerFrontToBackSynchronize->synchronizeAll();
        } elseif ('backToFront' === $direction) {
            $this->customerBackToFrontSynchronize->synchronizeAll();
        } else {
            throw new \InvalidArgumentException("Invalidate direction: {$direction}");
        }

        return 0;
    }
}