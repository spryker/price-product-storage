<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business\Provider;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductReadinessTransfer;
use Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface;

class StoragePriceProductConcreteReadinessProvider implements PriceProductConcreteReadinessProviderInterface
{
    /**
     * @var string
     */
    protected const TITLE_IN_STORAGE = 'Concrete product price in Storage';

    /**
     * @var string
     */
    protected const FALLBACK_VALUE_NO_STORES = '-';

    /**
     * @var string
     */
    protected const FORMAT_STORE_SEPARATOR = ', ';

    /**
     * @param \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface $priceProductStorageClient
     * @param \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        protected PriceProductStorageClientInterface $priceProductStorageClient,
        protected PriceProductStorageToStoreFacadeInterface $storeFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer $productConcreteReadinessRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer> $productReadinessTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>
     */
    public function provide(
        ProductConcreteReadinessRequestTransfer $productConcreteReadinessRequestTransfer,
        ArrayObject $productReadinessTransfers
    ): ArrayObject {
        $idProductConcrete = $productConcreteReadinessRequestTransfer->getProductConcrete()->getIdProductConcrete();

        $storeNames = $this->findStoresWithPrices($idProductConcrete);
        $values = $this->formatStoreNames($storeNames);

        $productReadinessTransfers->append(
            (new ProductReadinessTransfer())
                ->setTitle(static::TITLE_IN_STORAGE)
                ->setValues($values),
        );

        return $productReadinessTransfers;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return array<string>
     */
    protected function findStoresWithPrices(int $idProductConcrete): array
    {
        $storeNames = [];
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            if ($this->hasPriceDataInStorage($idProductConcrete, $storeTransfer->getName())) {
                $storeNames[] = $storeTransfer->getName();
            }
        }

        return $storeNames;
    }

    /**
     * @param int $idProductConcrete
     * @param string|null $storeName
     *
     * @return bool
     */
    protected function hasPriceDataInStorage(int $idProductConcrete, ?string $storeName = null): bool
    {
        $priceProductTransfers = $this->priceProductStorageClient->getPriceProductConcreteTransfers($idProductConcrete, $storeName);

        return (bool)$priceProductTransfers;
    }

    /**
     * @param array<string> $storeNames
     *
     * @return array<string>
     */
    protected function formatStoreNames(array $storeNames): array
    {
        if (!$storeNames) {
            return [static::FALLBACK_VALUE_NO_STORES];
        }

        return [implode(static::FORMAT_STORE_SEPARATOR, $storeNames)];
    }
}
