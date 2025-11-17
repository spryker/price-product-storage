<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business;

use Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\Provider\PriceProductAbstractReadinessProviderInterface;
use Spryker\Zed\PriceProductStorage\Business\Provider\PriceProductConcreteReadinessProviderInterface;
use Spryker\Zed\PriceProductStorage\Business\Provider\StoragePriceProductAbstractReadinessProvider;
use Spryker\Zed\PriceProductStorage\Business\Provider\StoragePriceProductConcreteReadinessProvider;
use Spryker\Zed\PriceProductStorage\Business\Provider\StorageTablePriceProductAbstractReadinessProvider;
use Spryker\Zed\PriceProductStorage\Business\Provider\StorageTablePriceProductConcreteReadinessProvider;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriter;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriter;
use Spryker\Zed\PriceProductStorage\PriceProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface getRepository()
 */
class PriceProductStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriterInterface
     */
    public function createPriceProductAbstractStorageWriter()
    {
        return new PriceProductAbstractStorageWriter(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriterInterface
     */
    public function createPriceProductConcreteStorageWriter()
    {
        return new PriceProductConcreteStorageWriter(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Provider\PriceProductAbstractReadinessProviderInterface
     */
    public function createStorageTablePriceProductAbstractReadinessProvider(): PriceProductAbstractReadinessProviderInterface
    {
        return new StorageTablePriceProductAbstractReadinessProvider(
            $this->getRepository(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Provider\PriceProductAbstractReadinessProviderInterface
     */
    public function createStoragePriceProductAbstractReadinessProvider(): PriceProductAbstractReadinessProviderInterface
    {
        return new StoragePriceProductAbstractReadinessProvider(
            $this->getPriceProductStorageClient(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Provider\PriceProductConcreteReadinessProviderInterface
     */
    public function createStorageTablePriceProductConcreteReadinessProvider(): PriceProductConcreteReadinessProviderInterface
    {
        return new StorageTablePriceProductConcreteReadinessProvider(
            $this->getRepository(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Provider\PriceProductConcreteReadinessProviderInterface
     */
    public function createStoragePriceProductConcreteReadinessProvider(): PriceProductConcreteReadinessProviderInterface
    {
        return new StoragePriceProductConcreteReadinessProvider(
            $this->getPriceProductStorageClient(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface
     */
    protected function getPriceProductStorageClient(): PriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }
}
