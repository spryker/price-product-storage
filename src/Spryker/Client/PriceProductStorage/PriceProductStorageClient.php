<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ItemValidationTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageFactory getFactory()
 */
class PriceProductStorageClient extends AbstractClient implements PriceProductStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getPriceProductAbstractTransfers(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createPriceAbstractStorageReader()
            ->findPriceProductAbstractTransfers($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getPriceProductConcreteTransfers(int $idProductConcrete): array
    {
        return $this->getFactory()
            ->createPriceConcreteStorageReader()
            ->findPriceProductConcreteTransfers($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getResolvedPriceProductConcreteTransfers(int $idProductConcrete, int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createPriceConcreteResolver()
            ->resolvePriceProductConcrete($idProductConcrete, $idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function getResolvedCurrentProductPriceTransfer(PriceProductFilterTransfer $priceProductFilterTransfer): CurrentProductPriceTransfer
    {
        return $this->getFactory()
            ->createPriceConcreteResolver()
            ->resolveCurrentProductPriceTransfer($priceProductFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validateItemProductPrice(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer
    {
        return $this->getFactory()
            ->createPriceProductItemValidator()
            ->validate($itemValidationTransfer);
    }
}
