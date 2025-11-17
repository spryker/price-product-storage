<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Persistence;

use Generated\Shared\Transfer\PriceProductAbstractStorageCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductConcreteStorageCriteriaTransfer;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStoragePersistenceFactory getFactory()
 */
interface PriceProductStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductAbstractStorageCriteriaTransfer $priceProductAbstractStorageCriteriaTransfer
     *
     * @return array<mixed>
     */
    public function getPriceProductAbstractsByCriteria(PriceProductAbstractStorageCriteriaTransfer $priceProductAbstractStorageCriteriaTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\PriceProductConcreteStorageCriteriaTransfer $priceProductConcreteStorageCriteriaTransfer
     *
     * @return array<mixed>
     */
    public function getPriceProductConcretesByCriteria(PriceProductConcreteStorageCriteriaTransfer $priceProductConcreteStorageCriteriaTransfer): array;
}
