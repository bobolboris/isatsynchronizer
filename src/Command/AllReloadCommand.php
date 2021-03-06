<?php

namespace App\Command;

use App\Contract\BackToFront\AttributeSynchronizerInterface;
use App\Contract\BackToFront\CategorySynchronizerInterface;
use App\Contract\BackToFront\ProductSynchronizerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AllReloadCommand extends Command
{
    protected static $defaultName = 'all:reload';

    /** @var AttributeSynchronizerInterface $attributeSynchronizer */
    protected $attributeSynchronizer;

    /** @var CategorySynchronizerInterface $categorySynchronizer */
    protected $categorySynchronizer;

    /** @var ProductSynchronizerInterface $productSynchronizer */
    protected $productSynchronizer;

    /**
     * AllReloadCommand constructor.
     * @param AttributeSynchronizerInterface $attributeSynchronizer
     * @param CategorySynchronizerInterface $categorySynchronizer
     * @param ProductSynchronizerInterface $productSynchronizer
     */
    public function __construct(
        AttributeSynchronizerInterface $attributeSynchronizer,
        CategorySynchronizerInterface $categorySynchronizer,
        ProductSynchronizerInterface $productSynchronizer
    )
    {
        $this->attributeSynchronizer = $attributeSynchronizer;
        $this->categorySynchronizer = $categorySynchronizer;
        $this->productSynchronizer = $productSynchronizer;
        parent::__construct();
    }

    /**
     *
     */
    protected function configure(): void
    {
        $this->setDescription('Reload all');
        $this->addArgument('reloadImage', InputArgument::OPTIONAL, 'Reload image');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->categorySynchronizer->load();
        $this->attributeSynchronizer->load();
        $this->productSynchronizer->load();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reloadImage = $input->getArgument('reloadImage') !== null;

        $this->categorySynchronizer->reload($reloadImage);
        $this->attributeSynchronizer->reload();
        $this->productSynchronizer->reload($reloadImage);

        return Command::SUCCESS;
    }
}