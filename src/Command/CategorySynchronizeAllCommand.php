<?php

namespace App\Command;

use App\Service\Synchronizer\BackToFront\CategorySynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CategorySynchronizeAllCommand extends Command
{
    protected static $defaultName = 'category:synchronize:all';

    /** @var CategorySynchronizer $categorySynchronize */
    protected $categorySynchronize;

    /**
     * CategorySynchronizeAllCommand constructor.
     * @param CategorySynchronizer $categorySynchronize
     */
    public function __construct(CategorySynchronizer $categorySynchronize)
    {
        $this->categorySynchronize = $categorySynchronize;
        parent::__construct();
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Synchronize categories');
        $this->addArgument('loadImage', InputArgument::OPTIONAL, 'Load image');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $loadImage = $input->getArgument('loadImage') !== null;
        $this->categorySynchronize->load()->synchronizeAll($loadImage);

        return 0;
    }
}
