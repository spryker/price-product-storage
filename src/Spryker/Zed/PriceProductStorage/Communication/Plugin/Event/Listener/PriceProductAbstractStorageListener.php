<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;

/**
 * @deprecated Use {@link \Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStoragePublishListener}
 *   and {@link \Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStorageUnpublishListener} instead.
 *
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceProductStorage\Communication\PriceProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 */
class PriceProductAbstractStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $productAbstractIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventEntityTransfers, SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT);

        if ($eventName === PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE || $eventName === PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE) {
            $this->getFacade()->publishPriceProductAbstract($productAbstractIds);
        }
    }
}
