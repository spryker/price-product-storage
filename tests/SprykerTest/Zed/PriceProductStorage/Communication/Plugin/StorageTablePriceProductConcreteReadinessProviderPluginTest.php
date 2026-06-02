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
use Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\Provider\StorageTablePriceProductConcreteReadinessProvider;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\ProductManagement\StorageTablePriceProductConcreteReadinessProviderPlugin;
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
    protected const string STORAGE_KEY = 'price-product-concrete:de:456';

    protected const string STORAGE_KEY_URL_PART = '/storage-gui/maintenance/key?key=';

    public function testProvideReturnsFallbackWhenNoDataExists(): void
    {
        // Arrange
        $plugin = $this->createPlugin(
            $this->createRepositoryMockReturning([]),
            $this->createStorageClientMockReturning([]),
        );

        // Act
        $result = $plugin->provide(
            $this->createRequest(456),
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result->getArrayCopy());
        $this->assertSame('-', $result->getArrayCopy()[0]->getValues()[0]);
    }

    public function testProvideReturnsStorageKeyLinkWhenStorageKeyExists(): void
    {
        // Arrange
        $plugin = $this->createPlugin(
            $this->createRepositoryMockReturning([
                $this->buildStorageRow('DE', static::STORAGE_KEY, null, null),
            ]),
            $this->createStorageClientMockReturning([]),
        );

        // Act
        $result = $plugin->provide($this->createRequest(456), new ArrayObject());

        // Assert
        $row = $result->getArrayCopy()[0]->getValues()[0];
        $this->assertStringContainsString(static::STORAGE_KEY_URL_PART . static::STORAGE_KEY, $row);
    }

    public function testProvideReturnsSyncedStatusWhenStorageMatchesDatabase(): void
    {
        // Arrange
        $dbData = ['prices' => ['EUR' => 100, 'USD' => 120]];
        $plugin = $this->createPlugin(
            $this->createRepositoryMockReturning([
                $this->buildStorageRow('DE', static::STORAGE_KEY, $dbData, '2024-01-15 10:30:00'),
            ]),
            $this->createStorageClientMockReturning([
                'kv:' . static::STORAGE_KEY => json_encode(array_merge($dbData, ['_timestamp' => 1705315800])),
            ]),
        );

        // Act
        $result = $plugin->provide($this->createRequest(456), new ArrayObject());

        // Assert
        $row = $result->getArrayCopy()[0]->getValues()[0];
        $this->assertStringContainsString('Synced', $row);
        $this->assertStringContainsString('EUR, USD', $row);
        $this->assertStringContainsString('DE', $row);
        $this->assertStringContainsString('2024-01-15 10:30:00 UTC', $row);
    }

    public function testProvideReturnsUnsyncedStatusWhenStorageKeyIsMissing(): void
    {
        // Arrange - storage key present in DB row but storage returns nothing for it
        $plugin = $this->createPlugin(
            $this->createRepositoryMockReturning([
                $this->buildStorageRow('DE', static::STORAGE_KEY, ['prices' => []], null),
            ]),
            $this->createStorageClientMockReturning([]),
        );

        // Act
        $result = $plugin->provide($this->createRequest(456), new ArrayObject());

        // Assert
        $row = $result->getArrayCopy()[0]->getValues()[0];
        $this->assertStringContainsString('Unsynced', $row);
        $this->assertStringContainsString(static::STORAGE_KEY_URL_PART . static::STORAGE_KEY, $row);
    }

    public function testProvideReturnsUnsyncedStatusWhenRowHasNoStorageKey(): void
    {
        // Arrange - no key column means product was never synced to storage
        $plugin = $this->createPlugin(
            $this->createRepositoryMockReturning([
                $this->buildStorageRow('US', null, null, null),
            ]),
            $this->createStorageClientMockReturning([]),
        );

        // Act
        $result = $plugin->provide($this->createRequest(456), new ArrayObject());

        // Assert
        $row = $result->getArrayCopy()[0]->getValues()[0];
        $this->assertStringContainsString('Unsynced', $row);
        // No link when key is absent
        $this->assertStringNotContainsString(static::STORAGE_KEY_URL_PART, $row);
    }

    public function testProvideReturnsUnsyncedStatusWhenDataDiffersFromStorage(): void
    {
        // Arrange
        $dbData = ['prices' => ['EUR' => 100]];
        $storageData = ['prices' => ['EUR' => 200]]; // different price
        $plugin = $this->createPlugin(
            $this->createRepositoryMockReturning([
                $this->buildStorageRow('DE', static::STORAGE_KEY, $dbData, null),
            ]),
            $this->createStorageClientMockReturning([
                'kv:' . static::STORAGE_KEY => json_encode(array_merge($storageData, ['_timestamp' => 1705315800])),
            ]),
        );

        // Act
        $result = $plugin->provide($this->createRequest(456), new ArrayObject());

        // Assert
        $this->assertStringContainsString('Unsynced', $result->getArrayCopy()[0]->getValues()[0]);
    }

    public function testProvideReturnsOneValuePerStorageRow(): void
    {
        // Arrange
        $plugin = $this->createPlugin(
            $this->createRepositoryMockReturning([
                $this->buildStorageRow('DE', null, null, null),
                $this->buildStorageRow('US', null, null, null),
            ]),
            $this->createStorageClientMockReturning([]),
        );

        // Act
        $result = $plugin->provide($this->createRequest(456), new ArrayObject());

        // Assert
        $this->assertCount(2, $result->getArrayCopy()[0]->getValues());
    }

    protected function createPlugin(
        PriceProductStorageRepositoryInterface $repositoryMock,
        PriceProductStorageClientInterface $storageClientMock,
    ): StorageTablePriceProductConcreteReadinessProviderPlugin {
        $provider = new StorageTablePriceProductConcreteReadinessProvider(
            $repositoryMock,
            $storageClientMock,
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
    protected function createRepositoryMockReturning(array $data): PriceProductStorageRepositoryInterface
    {
        $mock = $this->getMockBuilder(PriceProductStorageRepositoryInterface::class)->getMock();
        $mock->method('getPriceProductConcretesByCriteria')->willReturn($data);

        return $mock;
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStorageClientMockReturning(array $data): PriceProductStorageClientInterface
    {
        $mock = $this->getMockBuilder(PriceProductStorageClientInterface::class)->getMock();
        $mock->method('getRawPriceCollection')->willReturn($data);

        return $mock;
    }

    protected function createRequest(int $idProductConcrete): ProductConcreteReadinessRequestTransfer
    {
        return (new ProductConcreteReadinessRequestTransfer())
            ->setProductConcrete((new ProductConcreteTransfer())->setIdProductConcrete($idProductConcrete));
    }

    /**
     * @param array<string, mixed>|null $data
     */
    protected function buildStorageRow(string $store, ?string $storageKey, ?array $data, ?string $updatedAt): array
    {
        return [
            'store' => $store,
            'key' => $storageKey,
            'data' => $data,
            'updated_at' => $updatedAt,
        ];
    }
}
