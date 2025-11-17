<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business\Provider;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductAbstractStorageConditionsTransfer;
use Generated\Shared\Transfer\PriceProductAbstractStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductReadinessTransfer;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface;

class StorageTablePriceProductAbstractReadinessProvider implements PriceProductAbstractReadinessProviderInterface
{
    /**
     * @var string
     */
    protected const TITLE_IN_STORAGE_TABLE = 'Abstract product price in a table spy_price_product_abstract_storage';

    /**
     * @var string
     */
    protected const FALLBACK_VALUE_NO_STORES = '-';

    /**
     * @var string
     */
    protected const FORMAT_STORE_SEPARATOR = ', ';

    /**
     * @var string
     */
    protected const KEY_STORE = 'store';

    /**
     * @param \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface $priceProductStorageRepository
     * @param \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        protected PriceProductStorageRepositoryInterface $priceProductStorageRepository,
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

        $criteriaTransfer = (new PriceProductAbstractStorageCriteriaTransfer())
            ->setPriceProductAbstractStorageConditions(
                (new PriceProductAbstractStorageConditionsTransfer())
                    ->setProductAbstractIds([$idProductAbstract]),
            );

        $priceProductStorageData = $this->priceProductStorageRepository->getPriceProductAbstractsByCriteria($criteriaTransfer);

        $storeNames = $this->extractStoreNames($priceProductStorageData);
        $values = $this->formatStoreNames($storeNames);

        $productReadinessTransfers->append(
            (new ProductReadinessTransfer())
                ->setTitle(static::TITLE_IN_STORAGE_TABLE)
                ->setValues($values),
        );

        return $productReadinessTransfers;
    }

    /**
     * @param array<array<string, mixed>> $priceProductStorageData
     *
     * @return array<string>
     */
    protected function extractStoreNames(array $priceProductStorageData): array
    {
        $storeNames = [];

        foreach ($priceProductStorageData as $priceProductAbstractStorageArray) {
            $storeName = $priceProductAbstractStorageArray[static::KEY_STORE] ?? null;

            if ($storeName !== null) {
                $storeNames[] = $storeName;
            }
        }

        return array_unique($storeNames);
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
