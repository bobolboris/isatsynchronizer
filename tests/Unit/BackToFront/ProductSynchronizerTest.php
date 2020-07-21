<?php

namespace App\Tests\Unit\BackToFront;

use App\Service\Synchronizer\BackToFront\ProductSynchronizer;
use App\Tests\Unit\BackToFront\ProductSynchronizerTest\CreateOrUpdateProductTrait;
use App\Tests\Unit\BackToFront\ProductSynchronizerTest\UpdateProductCategoryFrontFromProductBackTrait;
use App\Tests\Unit\BackToFront\ProductSynchronizerTest\UpdateProductStoreFrontFromProductBackTrait;
use App\Tests\WebTestCase;

class ProductSynchronizerTest extends WebTestCase
{
    use CreateOrUpdateProductTrait;
    use UpdateProductCategoryFrontFromProductBackTrait;
    use UpdateProductStoreFrontFromProductBackTrait;

    /** @var ProductSynchronizer $productSynchronizer */
    protected $productSynchronizer;

    /**
     *
     */
    protected function setUp()
    {
        self::bootKernel();
        $this->productSynchronizer = self::$container->get(ProductSynchronizer::class);
    }
}