<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business\Provider;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductReadinessTransfer;
use Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface;

class StoragePriceProductAbstractReadinessProvider implements PriceProductAbstractReadinessProviderInterface
{
    /**
     * @var string
     */
    protected const TITLE_IN_STORAGE = 'Abstract product price in Storage';

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
     * @param \Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer $productAbstractReadinessRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer> $productReadinessTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>
     */
    public function provide(
        ProductAbstractReadinessRequestTransfer $productAbstractReadinessRequestTransfer,
        ArrayObject $productReadinessTransfers
    ): ArrayObject {
        $idProductAbstract = $productAbstractReadinessRequestTransfer->getProductAbstract()->getIdProductAbstract();

        $storeNames = $this->findStoresWithPrices($idProductAbstract);
        $values = $this->formatStoreNames($storeNames);

        $productReadinessTransfers->append(
            (new ProductReadinessTransfer())
                ->setTitle(static::TITLE_IN_STORAGE)
                ->setValues($values),
        );

        return $productReadinessTransfers;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<string>
     */
    protected function findStoresWithPrices(int $idProductAbstract): array
    {
        $storeNames = [];
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            if ($this->hasPriceDataInStorage($idProductAbstract, $storeTransfer->getName())) {
                $storeNames[] = $storeTransfer->getName();
            }
        }

        return $storeNames;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function hasPriceDataInStorage(int $idProductAbstract, ?string $storeName = null): bool
    {
        $priceProductTransfers = $this->priceProductStorageClient->getPriceProductAbstractTransfers($idProductAbstract, $storeName);

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
