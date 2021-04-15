<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductStorageConfig extends AbstractSharedConfig
{
    /**
     * @deprecated Use {@link \Spryker\Shared\PriceProductStorage\PriceProductStorageConfig::PRICE_DATA_BY_PRICE_TYPE} instead.
     *
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA
     */
    public const PRICE_DATA = 'priceData';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA_BY_PRICE_TYPE
     */
    public const PRICE_DATA_BY_PRICE_TYPE = 'priceDataByPriceType';

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    public const PRICE_NET_MODE = 'NET_MODE';

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    public const PRICE_GROSS_MODE = 'GROSS_MODE';

    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_MODES
     */
    public const PRICE_MODES = [
        'NET_MODE',
        'GROSS_MODE',
    ];
}
