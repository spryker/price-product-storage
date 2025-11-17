<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Persistence;

use Generated\Shared\Transfer\PriceProductAbstractStorageCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductConcreteStorageCriteriaTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStoragePersistenceFactory getFactory()
 */
class PriceProductStorageRepository extends AbstractRepository implements PriceProductStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductAbstractStorageCriteriaTransfer $priceProductAbstractStorageCriteriaTransfer
     *
     * @return array<mixed>
     */
    public function getPriceProductAbstractsByCriteria(PriceProductAbstractStorageCriteriaTransfer $priceProductAbstractStorageCriteriaTransfer): array
    {
        $query = $this->getFactory()->createSpyPriceAbstractStorageQuery();

        $query = $this->applyPriceProductAbstractStorageConditions($query, $priceProductAbstractStorageCriteriaTransfer);

        $priceProductAbstractStorageEntities = $query->find();

        return $this->getFactory()
            ->createPriceProductStorageMapper()
            ->mapSpyPriceProductAbstractStorageEntitiesToArrays($priceProductAbstractStorageEntities);
    }

    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery $query
     * @param \Generated\Shared\Transfer\PriceProductAbstractStorageCriteriaTransfer $priceProductAbstractStorageCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery
     */

    /**
     * @param \Generated\Shared\Transfer\PriceProductConcreteStorageCriteriaTransfer $priceProductConcreteStorageCriteriaTransfer
     *
     * @return array<mixed>
     */
    public function getPriceProductConcretesByCriteria(PriceProductConcreteStorageCriteriaTransfer $priceProductConcreteStorageCriteriaTransfer): array
    {
        $query = $this->getFactory()->createSpyPriceConcreteStorageQuery();

        $query = $this->applyPriceProductConcreteStorageConditions($query, $priceProductConcreteStorageCriteriaTransfer);

        $priceProductConcreteStorageEntities = $query->find();

        return $this->getFactory()
            ->createPriceProductStorageMapper()
            ->mapSpyPriceProductConcreteStorageEntitiesToArrays($priceProductConcreteStorageEntities);
    }

    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery $query
     * @param \Generated\Shared\Transfer\PriceProductAbstractStorageCriteriaTransfer $priceProductAbstractStorageCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery
     */
    protected function applyPriceProductAbstractStorageConditions(
        $query,
        PriceProductAbstractStorageCriteriaTransfer $priceProductAbstractStorageCriteriaTransfer
    ) {
        $priceProductAbstractStorageConditionsTransfer = $priceProductAbstractStorageCriteriaTransfer->getPriceProductAbstractStorageConditions();

        if (!$priceProductAbstractStorageConditionsTransfer) {
            return $query;
        }

        $productAbstractIds = $priceProductAbstractStorageConditionsTransfer->getProductAbstractIds();
        if ($productAbstractIds) {
            $query->filterByFkProductAbstract_In($productAbstractIds);
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery $query
     * @param \Generated\Shared\Transfer\PriceProductConcreteStorageCriteriaTransfer $priceProductConcreteStorageCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery
     */
    protected function applyPriceProductConcreteStorageConditions(
        $query,
        PriceProductConcreteStorageCriteriaTransfer $priceProductConcreteStorageCriteriaTransfer
    ) {
        $priceProductConcreteStorageConditionsTransfer = $priceProductConcreteStorageCriteriaTransfer->getPriceProductConcreteStorageConditions();

        if (!$priceProductConcreteStorageConditionsTransfer) {
            return $query;
        }

        $productConcreteIds = $priceProductConcreteStorageConditionsTransfer->getProductConcreteIds();
        if ($productConcreteIds) {
            $query->filterByFkProduct_In($productConcreteIds);
        }

        return $query;
    }
}
