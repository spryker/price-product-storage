<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductStorage\Communication\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\Provider\StorageTablePriceProductConcreteReadinessProvider;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\ProductManagement\StorageTablePriceProductConcreteReadinessProviderPlugin;
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
 * @group StorageTablePriceProductConcreteReadinessProviderPluginTest
 * Add your own group annotations below this line
 */
class StorageTablePriceProductConcreteReadinessProviderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideReturnsStoresWithPriceData(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMocks($this->createPriceProductStorageRepositoryMockWithData());
        $productConcrete = (new ProductConcreteTransfer())->setIdProductConcrete(123);
        $productConcreteReadinessRequestTransfer = (new ProductConcreteReadinessRequestTransfer())
            ->setProductConcrete($productConcrete);

        // Act
        $result = $plugin->provide(
            $productConcreteReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result->getArrayCopy());
        $productReadiness = $result->getArrayCopy()[0];
        $this->assertSame('Concrete product price in a table spy_price_product_concrete_storage', $productReadiness->getTitle());
        $this->assertSame('DE, US', $productReadiness->getValues()[0]);
    }

    /**
     * @return void
     */
    public function testProvideReturnsDashWhenNoPriceDataExists(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMocks($this->createPriceProductStorageRepositoryMockWithNoData());
        $productConcrete = (new ProductConcreteTransfer())->setIdProductConcrete(456);
        $productConcreteReadinessRequestTransfer = (new ProductConcreteReadinessRequestTransfer())
            ->setProductConcrete($productConcrete);

        // Act
        $result = $plugin->provide(
            $productConcreteReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result->getArrayCopy());
        $productReadiness = $result->getArrayCopy()[0];
        $this->assertSame('Concrete product price in a table spy_price_product_concrete_storage', $productReadiness->getTitle());
        $this->assertSame('-', $productReadiness->getValues()[0]);
    }

    /**
     * @return void
     */
    public function testProvideReturnsOnlyOneStoreWhenPartialStoreCoverage(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMocks($this->createPriceProductStorageRepositoryMockWithOneStore());
        $productConcrete = (new ProductConcreteTransfer())->setIdProductConcrete(789);
        $productConcreteReadinessRequestTransfer = (new ProductConcreteReadinessRequestTransfer())
            ->setProductConcrete($productConcrete);

        // Act
        $result = $plugin->provide(
            $productConcreteReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result->getArrayCopy());
        $productReadiness = $result->getArrayCopy()[0];
        $this->assertSame('Concrete product price in a table spy_price_product_concrete_storage', $productReadiness->getTitle());
        $this->assertSame('DE', $productReadiness->getValues()[0]);
    }

    /**
     * @return void
     */
    public function testProvideRemovesDuplicateStores(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMocks($this->createPriceProductStorageRepositoryMockWithDuplicateStores());
        $productConcrete = (new ProductConcreteTransfer())->setIdProductConcrete(999);
        $productConcreteReadinessRequestTransfer = (new ProductConcreteReadinessRequestTransfer())
            ->setProductConcrete($productConcrete);

        // Act
        $result = $plugin->provide(
            $productConcreteReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertSame('DE, US', $result->getArrayCopy()[0]->getValues()[0]);
    }

    /**
     * @param \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface $repositoryMock
     *
     * @return \Spryker\Zed\PriceProductStorage\Communication\Plugin\ProductManagement\StorageTablePriceProductConcreteReadinessProviderPlugin
     */
    protected function createPluginWithMocks(PriceProductStorageRepositoryInterface $repositoryMock): StorageTablePriceProductConcreteReadinessProviderPlugin
    {
        $provider = new StorageTablePriceProductConcreteReadinessProvider(
            $repositoryMock,
            $this->createStoreFacadeMock(),
        );

        $factoryMock = $this->getMockBuilder(PriceProductStorageBusinessFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createStorageTablePriceProductConcreteReadinessProvider'])
            ->getMock();

        $factoryMock->method('createStorageTablePriceProductConcreteReadinessProvider')->willReturn($provider);

        $plugin = new StorageTablePriceProductConcreteReadinessProviderPlugin();
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
                'fk_product' => 123,
                'store' => 'DE',
                'data' => '{}',
            ],
            [
                'fk_product' => 123,
                'store' => 'US',
                'data' => '{}',
            ],
        ];

        $repositoryMock->method('getPriceProductConcretesByCriteria')->willReturn($priceProductStorageData);

        return $repositoryMock;
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPriceProductStorageRepositoryMockWithNoData(): PriceProductStorageRepositoryInterface
    {
        $repositoryMock = $this->getMockBuilder(PriceProductStorageRepositoryInterface::class)
            ->getMock();

        $repositoryMock->method('getPriceProductConcretesByCriteria')->willReturn([]);

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
                'fk_product' => 789,
                'store' => 'DE',
                'data' => '{}',
            ],
        ];

        $repositoryMock->method('getPriceProductConcretesByCriteria')->willReturn($priceProductStorageData);

        return $repositoryMock;
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPriceProductStorageRepositoryMockWithDuplicateStores(): PriceProductStorageRepositoryInterface
    {
        $repositoryMock = $this->getMockBuilder(PriceProductStorageRepositoryInterface::class)
            ->getMock();

        $priceProductStorageData = [
            [
                'fk_product' => 999,
                'store' => 'DE',
                'data' => '{}',
            ],
            [
                'fk_product' => 999,
                'store' => 'US',
                'data' => '{}',
            ],
            [
                'fk_product' => 999,
                'store' => 'DE',
                'data' => '{}',
            ],
        ];

        $repositoryMock->method('getPriceProductConcretesByCriteria')->willReturn($priceProductStorageData);

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
