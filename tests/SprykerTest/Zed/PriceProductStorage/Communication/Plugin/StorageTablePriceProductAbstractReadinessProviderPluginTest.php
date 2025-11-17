<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductStorage\Communication\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\Provider\StorageTablePriceProductAbstractReadinessProvider;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\ProductManagement\StorageTablePriceProductAbstractReadinessProviderPlugin;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductStorage
 * @group Communication
 * @group Plugin
 * @group StorageTablePriceProductAbstractReadinessProviderPluginTest
 * Add your own group annotations below this line
 */
class StorageTablePriceProductAbstractReadinessProviderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideReturnsStoresWithPriceData(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMocks($this->createPriceProductStorageRepositoryMockWithData());
        $productAbstract = (new ProductAbstractTransfer())->setIdProductAbstract(123);
        $productAbstractReadinessRequestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductAbstract($productAbstract);

        // Act
        $result = $plugin->provide(
            $productAbstractReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result->getArrayCopy());
        $productReadiness = $result->getArrayCopy()[0];
        $this->assertSame('Abstract product price in a table spy_price_product_abstract_storage', $productReadiness->getTitle());
        $this->assertSame('DE, US', $productReadiness->getValues()[0]);
    }

    /**
     * @return void
     */
    public function testProvideReturnsNoWhenNoPriceDataExists(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMocks($this->createPriceProductStorageRepositoryMockWithNoData());
        $productAbstract = (new ProductAbstractTransfer())->setIdProductAbstract(456);
        $productAbstractReadinessRequestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductAbstract($productAbstract);

        // Act
        $result = $plugin->provide(
            $productAbstractReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result->getArrayCopy());
        $productReadiness = $result->getArrayCopy()[0];
        $this->assertSame('Abstract product price in a table spy_price_product_abstract_storage', $productReadiness->getTitle());
        $this->assertSame('-', $productReadiness->getValues()[0]);
    }

    /**
     * @return void
     */
    public function testProvideReturnsOnlyOneStoreWhenPartialStoreCoverage(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMocks($this->createPriceProductStorageRepositoryMockWithOneStore());
        $productAbstract = (new ProductAbstractTransfer())->setIdProductAbstract(789);
        $productAbstractReadinessRequestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductAbstract($productAbstract);

        // Act
        $result = $plugin->provide(
            $productAbstractReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result->getArrayCopy());
        $productReadiness = $result->getArrayCopy()[0];
        $this->assertSame('Abstract product price in a table spy_price_product_abstract_storage', $productReadiness->getTitle());
        $this->assertSame('DE', $productReadiness->getValues()[0]);
    }

    /**
     * @param \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface $repositoryMock
     *
     * @return \Spryker\Zed\PriceProductStorage\Communication\Plugin\ProductManagement\StorageTablePriceProductAbstractReadinessProviderPlugin
     */
    protected function createPluginWithMocks(PriceProductStorageRepositoryInterface $repositoryMock): StorageTablePriceProductAbstractReadinessProviderPlugin
    {
        $provider = new StorageTablePriceProductAbstractReadinessProvider(
            $repositoryMock,
            $this->createStoreFacadeMock(),
        );

        $factoryMock = $this->getMockBuilder(PriceProductStorageBusinessFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createStorageTablePriceProductAbstractReadinessProvider'])
            ->getMock();

        $factoryMock->method('createStorageTablePriceProductAbstractReadinessProvider')->willReturn($provider);

        $plugin = new StorageTablePriceProductAbstractReadinessProviderPlugin();
        $plugin->setBusinessFactory($factoryMock);

        return $plugin;
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPriceProductStorageRepositoryMockWithData(): PriceProductStorageRepositoryInterface
    {
        $repositoryMock = $this->getMockBuilder(PriceProductStorageRepositoryInterface::class)
            ->getMock();

        $priceProductStorageData = [
            [
                'fk_product_abstract' => 123,
                'store' => 'DE',
                'data' => '{}',
            ],
            [
                'fk_product_abstract' => 123,
                'store' => 'US',
                'data' => '{}',
            ],
        ];

        $repositoryMock->method('getPriceProductAbstractsByCriteria')->willReturn($priceProductStorageData);

        return $repositoryMock;
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPriceProductStorageRepositoryMockWithNoData(): PriceProductStorageRepositoryInterface
    {
        $repositoryMock = $this->getMockBuilder(PriceProductStorageRepositoryInterface::class)
            ->getMock();

        $repositoryMock->method('getPriceProductAbstractsByCriteria')->willReturn([]);

        return $repositoryMock;
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPriceProductStorageRepositoryMockWithOneStore(): PriceProductStorageRepositoryInterface
    {
        $repositoryMock = $this->getMockBuilder(PriceProductStorageRepositoryInterface::class)
            ->getMock();

        $priceProductStorageData = [
            [
                'fk_product_abstract' => 789,
                'store' => 'DE',
                'data' => '{}',
            ],
        ];

        $repositoryMock->method('getPriceProductAbstractsByCriteria')->willReturn($priceProductStorageData);

        return $repositoryMock;
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStoreFacadeMock(): PriceProductStorageToStoreFacadeInterface
    {
        $storeFacadeMock = $this->getMockBuilder(PriceProductStorageToStoreFacadeInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllStores'])
            ->getMock();

        $stores = [
            (new StoreTransfer())->setName('DE'),
            (new StoreTransfer())->setName('US'),
        ];

        $storeFacadeMock->method('getAllStores')->willReturn($stores);

        return $storeFacadeMock;
    }
}
