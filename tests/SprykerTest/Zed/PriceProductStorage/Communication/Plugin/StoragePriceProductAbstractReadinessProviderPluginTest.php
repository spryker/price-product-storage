<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductStorage\Communication\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\Provider\StoragePriceProductAbstractReadinessProvider;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\ProductManagement\StoragePriceProductAbstractReadinessProviderPlugin;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductStorage
 * @group Communication
 * @group Plugin
 * @group StoragePriceProductAbstractReadinessProviderPluginTest
 * Add your own group annotations below this line
 */
class StoragePriceProductAbstractReadinessProviderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideReturnsStoresWithPriceData(): void
    {
        // Arrange
        $idProductAbstract = 123;
        $plugin = $this->createPluginWithMocks(
            $this->createPriceProductStorageClientMock([
                'DE' => [new PriceProductTransfer()],
                'US' => [new PriceProductTransfer()],
                'FR' => [],
            ]),
        );

        $requestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductAbstract((new ProductAbstractTransfer())->setIdProductAbstract($idProductAbstract));

        // Act
        $result = $plugin->provide($requestTransfer, new ArrayObject());

        // Assert
        $this->assertCount(1, $result->getArrayCopy());
        $productReadiness = $result->getArrayCopy()[0];
        $this->assertSame('Abstract product price in Storage', $productReadiness->getTitle());
        $this->assertSame('DE, US', $productReadiness->getValues()[0]);
    }

    /**
     * @return void
     */
    public function testProvideReturnsDashWhenNoPriceDataExists(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMocks(
            $this->createPriceProductStorageClientMock([
                'DE' => [],
                'US' => [],
            ]),
        );

        $requestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductAbstract((new ProductAbstractTransfer())->setIdProductAbstract(456));

        // Act
        $result = $plugin->provide($requestTransfer, new ArrayObject());

        // Assert
        $this->assertSame('-', $result->getArrayCopy()[0]->getValues()[0]);
    }

    /**
     * @return void
     */
    public function testProvideReturnsDashWhenNoStoresProvided(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMocks(
            $this->createPriceProductStorageClientMock([]),
        );

        $requestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductAbstract((new ProductAbstractTransfer())->setIdProductAbstract(789));

        // Act
        $result = $plugin->provide($requestTransfer, new ArrayObject());

        // Assert
        $this->assertSame('-', $result->getArrayCopy()[0]->getValues()[0]);
    }

    /**
     * @param \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface $priceProductStorageClientMock
     *
     * @return \Spryker\Zed\PriceProductStorage\Communication\Plugin\ProductManagement\StoragePriceProductAbstractReadinessProviderPlugin
     */
    protected function createPluginWithMocks(
        PriceProductStorageClientInterface $priceProductStorageClientMock
    ): StoragePriceProductAbstractReadinessProviderPlugin {
        $storeFacadeMock = $this->createStoreFacadeMock();

        $provider = new StoragePriceProductAbstractReadinessProvider(
            $priceProductStorageClientMock,
            $storeFacadeMock,
        );

        $factoryMock = $this->getMockBuilder(PriceProductStorageBusinessFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createStoragePriceProductAbstractReadinessProvider'])
            ->getMock();

        $factoryMock->method('createStoragePriceProductAbstractReadinessProvider')->willReturn($provider);

        $plugin = new StoragePriceProductAbstractReadinessProviderPlugin();
        $plugin->setBusinessFactory($factoryMock);

        return $plugin;
    }

    /**
     * @param array<string, array<\Generated\Shared\Transfer\PriceProductTransfer>> $priceDataByStore
     *
     * @return \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPriceProductStorageClientMock(array $priceDataByStore): PriceProductStorageClientInterface
    {
        $priceProductStorageClientMock = $this->getMockBuilder(PriceProductStorageClientInterface::class)
            ->getMock();

        $priceProductStorageClientMock->method('getPriceProductAbstractTransfers')
            ->willReturnCallback(function (int $idProductAbstract, ?string $storeName) use ($priceDataByStore) {
                return $priceDataByStore[$storeName] ?? [];
            });

        return $priceProductStorageClientMock;
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
            (new StoreTransfer())->setName('FR'),
        ];

        $storeFacadeMock->method('getAllStores')->willReturn($stores);

        return $storeFacadeMock;
    }
}
