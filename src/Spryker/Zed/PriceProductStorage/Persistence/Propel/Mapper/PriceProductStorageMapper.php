<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Persistence\Propel\Mapper;

use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage;
use Propel\Runtime\Collection\Collection;

class PriceProductStorageMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage> $priceProductAbstractStorageEntities
     *
     * @return array<array<string, mixed>>
     */
    public function mapSpyPriceProductAbstractStorageEntitiesToArrays(Collection $priceProductAbstractStorageEntities): array
    {
        $priceProductAbstractStorageArrays = [];

        foreach ($priceProductAbstractStorageEntities as $priceProductAbstractStorageEntity) {
            $priceProductAbstractStorageArrays[] = $this->mapSpyPriceProductAbstractStorageEntityToArray($priceProductAbstractStorageEntity);
        }

        return $priceProductAbstractStorageArrays;
    }

    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage $priceProductAbstractStorageEntity
     *
     * @return array<string, mixed>
     */
    protected function mapSpyPriceProductAbstractStorageEntityToArray(SpyPriceProductAbstractStorage $priceProductAbstractStorageEntity): array
    {
        return $priceProductAbstractStorageEntity->toArray();
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage> $priceProductConcreteStorageEntities
     *
     * @return array<array<string, mixed>>
     */
    public function mapSpyPriceProductConcreteStorageEntitiesToArrays(Collection $priceProductConcreteStorageEntities): array
    {
        $priceProductConcreteStorageArrays = [];

        foreach ($priceProductConcreteStorageEntities as $priceProductConcreteStorageEntity) {
            $priceProductConcreteStorageArrays[] = $this->mapSpyPriceProductConcreteStorageEntityToArray($priceProductConcreteStorageEntity);
        }

        return $priceProductConcreteStorageArrays;
    }

    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage $priceProductConcreteStorageEntity
     *
     * @return array<string, mixed>
     */
    protected function mapSpyPriceProductConcreteStorageEntityToArray(SpyPriceProductConcreteStorage $priceProductConcreteStorageEntity): array
    {
        return $priceProductConcreteStorageEntity->toArray();
    }
}
