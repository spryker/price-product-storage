<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business\Provider;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\PriceProductAbstractStorageConditionsTransfer;
use Generated\Shared\Transfer\PriceProductAbstractStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductReadinessTransfer;
use Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageRepositoryInterface;

class StorageTablePriceProductAbstractReadinessProvider implements PriceProductAbstractReadinessProviderInterface
{
    protected const string TITLE_IN_STORAGE_TABLE = 'Abstract product price in a table spy_price_product_abstract_storage';

    protected const string FALLBACK_VALUE = '-';

    protected const string FORMAT_CURRENCY_SEPARATOR = ', ';

    protected const string KEY_STORE = 'store';

    protected const string KEY_DATA = 'data';

    protected const string KEY_PRICES = 'prices';

    protected const string KEY_UPDATED_AT = 'updated_at';

    protected const string KEY_STORAGE_KEY = 'key';

    protected const string KEY_STORAGE_TIMESTAMP = '_timestamp';

    protected const string FORMAT_DATE_OUTPUT = 'Y-m-d H:i:s';

    protected const string FORMAT_DATE_WITH_UTC = '%s UTC';

    protected const string FORMAT_ROW = '%s, currencies: %s, storage: %s &mdash; Last updated. DB: <strong>%s</strong>. Storage: <strong>%s</strong>. Status: %s';

    protected const string FORMAT_STORAGE_KEY_LINK = '<a href="/storage-gui/maintenance/key?key=%s" target="_blank">%s</a>';

    protected const string STATUS_HTML_SYNCED = '<span style="color:green;font-weight:bold">Synced</span>';

    protected const string STATUS_HTML_UNSYNCED = '<span style="color:red;font-weight:bold">Unsynced</span>';

    public function __construct(
        protected PriceProductStorageRepositoryInterface $priceProductStorageRepository,
        protected PriceProductStorageClientInterface $priceProductStorageClient,
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer $productAbstractReadinessRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer> $productReadinessTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>
     */
    public function provide(
        ProductAbstractReadinessRequestTransfer $productAbstractReadinessRequestTransfer,
        ArrayObject $productReadinessTransfers
    ): ArrayObject {
        $idProductAbstract = $productAbstractReadinessRequestTransfer->getProductAbstract()->getIdProductAbstract();

        $criteriaTransfer = (new PriceProductAbstractStorageCriteriaTransfer())
            ->setPriceProductAbstractStorageConditions(
                (new PriceProductAbstractStorageConditionsTransfer())
                    ->setProductAbstractIds([$idProductAbstract]),
            );

        $priceProductStorageData = $this->priceProductStorageRepository->getPriceProductAbstractsByCriteria($criteriaTransfer);

        $productReadinessTransfers->append(
            (new ProductReadinessTransfer())
                ->setTitle(static::TITLE_IN_STORAGE_TABLE)
                ->setValues($this->buildRowValues($priceProductStorageData)),
        );

        return $productReadinessTransfers;
    }

    /**
     * @param array<array<string, mixed>> $priceProductStorageData
     *
     * @return array<string>
     */
    protected function buildRowValues(array $priceProductStorageData): array
    {
        if (!$priceProductStorageData) {
            return [static::FALLBACK_VALUE];
        }

        $storageKeys = array_filter(array_column($priceProductStorageData, static::KEY_STORAGE_KEY));
        $storageDataByKey = $storageKeys ? $this->priceProductStorageClient->getRawPriceCollection($storageKeys) : [];

        $values = [];

        foreach ($priceProductStorageData as $row) {
            $values[] = $this->formatRow($row, $storageDataByKey);
        }

        return $values;
    }

    /**
     * @param array<string, mixed> $row
     * @param array<string, string|null> $storageDataByKey
     */
    protected function formatRow(array $row, array $storageDataByKey): string
    {
        $storeName = $row[static::KEY_STORE] ?? static::FALLBACK_VALUE;
        $currencies = $this->extractCurrencies($row);
        $dbUpdatedAt = $row[static::KEY_UPDATED_AT] ?? null;
        $storageKey = $row[static::KEY_STORAGE_KEY] ?? null;

        $currenciesString = $currencies
            ? implode(static::FORMAT_CURRENCY_SEPARATOR, $currencies)
            : static::FALLBACK_VALUE;

        $dbFormatted = $dbUpdatedAt !== null
            ? sprintf(static::FORMAT_DATE_WITH_UTC, $this->formatUpdatedAt($dbUpdatedAt))
            : static::FALLBACK_VALUE;

        $rawStorageData = $storageKey !== null ? ($storageDataByKey['kv:' . $storageKey] ?? null) : null;
        $storageData = is_string($rawStorageData) ? json_decode($rawStorageData, true) : null;
        $storageFormatted = $this->formatStorageTimestamp($storageData);

        $statusHtml = $this->isSynced($row[static::KEY_DATA] ?? null, $storageData)
            ? static::STATUS_HTML_SYNCED
            : static::STATUS_HTML_UNSYNCED;

        $storageKeyLink = $storageKey !== null
            ? sprintf(static::FORMAT_STORAGE_KEY_LINK, $storageKey, $storageKey)
            : static::FALLBACK_VALUE;

        return sprintf(static::FORMAT_ROW, $storeName, $currenciesString, $storageKeyLink, $dbFormatted, $storageFormatted, $statusHtml);
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string>
     */
    protected function extractCurrencies(array $row): array
    {
        $prices = $row[static::KEY_DATA][static::KEY_PRICES] ?? [];

        return array_map('strval', array_keys($prices));
    }

    /**
     * @param array<string, mixed>|null $storageData
     */
    protected function formatStorageTimestamp(?array $storageData): string
    {
        if ($storageData === null) {
            return static::FALLBACK_VALUE;
        }

        $timestamp = $storageData[static::KEY_STORAGE_TIMESTAMP] ?? null;

        if ($timestamp === null) {
            return static::FALLBACK_VALUE;
        }

        $dateTime = (new DateTime())->setTimestamp((int)$timestamp);

        return sprintf(static::FORMAT_DATE_WITH_UTC, $dateTime->format(static::FORMAT_DATE_OUTPUT));
    }

    /**
     * @param array<string, mixed>|null $dbData
     * @param array<string, mixed>|null $storageData
     */
    protected function isSynced(?array $dbData, ?array $storageData): bool
    {
        if ($dbData === null || $storageData === null) {
            return false;
        }

        $storageDataWithoutTimestamp = $storageData;
        unset($storageDataWithoutTimestamp[static::KEY_STORAGE_TIMESTAMP]);

        return $dbData === $storageDataWithoutTimestamp;
    }

    protected function formatUpdatedAt(?string $updatedAt): string
    {
        if ($updatedAt === null) {
            return static::FALLBACK_VALUE;
        }

        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s.u', $updatedAt)
            ?: DateTime::createFromFormat('Y-m-d H:i:s', $updatedAt);

        if ($dateTime === false) {
            return $updatedAt;
        }

        return $dateTime->format(static::FORMAT_DATE_OUTPUT);
    }
}
