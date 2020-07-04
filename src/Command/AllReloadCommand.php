<?php

namespace App\Command;

use App\Service\Synchronizer\BackToFront\AttributeSynchronizer;
use App\Service\Synchronizer\BackToFront\CategorySynchronizer;
use App\Service\Synchronizer\BackToFront\ProductSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AllReloadCommand extends Command
{
    protected static $defaultName = 'all:reload';

    /** @var AttributeSynchronizer $attributeSynchronize */
    protected $attributeSynchronize;

    /** @var CategorySynchronizer $categorySynchronize */
    protected $categorySynchronize;

    /** @var ProductSynchronizer $productSynchronize */
    protected $productSynchronize;

    /**
     * AllReloadCommand constructor.
     * @param AttributeSynchronizer $attributeSynchronize
     * @param CategorySynchronizer $categorySynchronize
     * @param ProductSynchronizer $productSynchronize
     */
    public function __construct(
        AttributeSynchronizer $attributeSynchronize,
        CategorySynchronizer $categorySynchronize,
        ProductSynchronizer $productSynchronize
    )
    {
        $this->attributeSynchronize = $attributeSynchronize;
        $this->categorySynchronize = $categorySynchronize;
        $this->productSynchronize = $productSynchronize;
        parent::__construct();
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Reload all');
        $this->addArgument('reloadImage', InputArgument::OPTIONAL, 'Reload image');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reloadImage = $input->getArgument('reloadImage') !== null;
        $this->categorySynchronize->load()->reload($reloadImage);
        $this->attributeSynchronize->load()->reload();
        $this->productSynchronize->load()->reload($reloadImage);

        return 0;
    }
}