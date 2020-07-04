<?php

namespace App\Command;

use App\Service\Synchronizer\BackToFront\CustomerSynchronizer as CustomerSynchronize;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomerClearCommand extends Command
{
    protected static $defaultName = 'customer:clear';

    /** @var CustomerSynchronize $customerSynchronize */
    protected $customerSynchronize;

    /**
     * CustomerClearCommand constructor.
     * @param CustomerSynchronize $customerSynchronize
     */
    public function __construct(CustomerSynchronize $customerSynchronize)
    {
        $this->customerSynchronize = $customerSynchronize;
        parent::__construct();
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Clear customers');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->customerSynchronize->load()->clear();

        return 0;
    }
}