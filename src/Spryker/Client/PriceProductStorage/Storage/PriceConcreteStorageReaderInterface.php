<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

interface PriceConcreteStorageReaderInterface
{
    /**
     * @param int $idProductConcrete
     * @param string|null $storeName
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findPriceProductConcreteTransfers($idProductConcrete, ?string $storeName = null): array;
}
