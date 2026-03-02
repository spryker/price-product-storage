<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductStorage;

use Spryker\Zed\PriceProductStorage\PriceProductStorageConfig;

class PriceProductStorageConfigMock extends PriceProductStorageConfig
{
    public function isSendingToQueue(): bool
    {
        return false;
    }
}
