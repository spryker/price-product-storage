<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Persistence;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStoragePersistenceFactory getFactory()
 */
class PriceProductStorageQueryContainer extends AbstractQueryContainer implements PriceProductStorageQueryContainerInterface
{
    /**
     * @var string
     */
    public const ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @var string
     */
    public const ID_PRODUCT_CONCRETE = 'idProductConcrete';

    /**
     * @var string
     */
    public const SKU = 'sku';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractSkuByIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, static::SKU)
            ->select([
                static::ID_PRODUCT_ABSTRACT,
                static::SKU,
            ]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery
     */
    public function queryPriceAbstractStorageByPriceAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->createSpyPriceAbstractStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $priceTypeIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllProductAbstractIdsByPriceTypeIds(array $priceTypeIds)
    {
        return $this->getFactory()
            ->getPriceProductQueryContainer()
            ->queryPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->addAnd(SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT, null, Criteria::NOT_EQUAL)
            ->filterByFkPriceType_In($priceTypeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $priceTypeIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllProductIdsByPriceTypeIds(array $priceTypeIds)
    {
        return $this->getFactory()
            ->getPriceProductQueryContainer()
            ->queryPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT])
            ->addAnd(SpyPriceProductTableMap::COL_FK_PRODUCT, null, Criteria::NOT_EQUAL)
            ->filterByFkPriceType_In($priceTypeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $priceProductIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryAllProductIdsByPriceProductIds(array $priceProductIds)
    {
        return $this->getFactory()
            ->getPriceProductQueryContainer()
            ->queryPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT])
            ->addAnd(SpyPriceProductTableMap::COL_FK_PRODUCT, null, Criteria::NOT_EQUAL)
            ->filterByIdPriceProduct_In($priceProductIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $priceProductIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryAllProductAbstractIdsByPriceProductIds(array $priceProductIds)
    {
        return $this->getFactory()
            ->getPriceProductQueryContainer()
            ->queryPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->addAnd(SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT, null, Criteria::NOT_EQUAL)
            ->filterByIdPriceProduct_In($priceProductIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productConcreteIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductAbstractIdsByProductConcreteIds(array $productConcreteIds)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->filterByIdProduct_In($productConcreteIds)
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, static::ID_PRODUCT_CONCRETE)
            ->withColumn(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, static::ID_PRODUCT_ABSTRACT)
            ->select([
                static::ID_PRODUCT_CONCRETE,
                static::ID_PRODUCT_ABSTRACT,
            ]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productConcreteIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery
     */
    public function queryPriceConcreteStorageByProductIds(array $productConcreteIds)
    {
        return $this->getFactory()
            ->createSpyPriceConcreteStorageQuery()
            ->filterByFkProduct_In($productConcreteIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $priceProductStoreIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryProductAbstractIdsByPriceProductStoreIds(array $priceProductStoreIds): SpyPriceProductQuery
    {
        /** @phpstan-var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery */
        return $this->getFactory()
            ->getPriceProductQueryContainer()
            ->queryPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->filterByFkProductAbstract(null, Criteria::ISNOTNULL)
            ->usePriceProductStoreQuery()
                ->filterByIdPriceProductStore_In($priceProductStoreIds)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $priceProductStoreIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryProductConcreteIdsByPriceProductStoreIds(array $priceProductStoreIds): SpyPriceProductQuery
    {
        /** @phpstan-var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery */
        return $this->getFactory()
            ->getPriceProductQueryContainer()
            ->queryPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT])
            ->filterByFkProduct(null, Criteria::ISNOTNULL)
            ->usePriceProductStoreQuery()
                ->filterByIdPriceProductStore_In($priceProductStoreIds)
            ->endUse();
    }
}
