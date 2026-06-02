<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface;

class PriceProductStorageMultiReader implements PriceProductStorageMultiReaderInterface
{
    protected PriceProductStorageToStorageInterface $storageClient;

    public function __construct(PriceProductStorageToStorageInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * {@inheritDoc}
     */
    public function getRawPriceCollection(array $keys): array
    {
        return $this->storageClient->getMulti($keys);
    }
}
