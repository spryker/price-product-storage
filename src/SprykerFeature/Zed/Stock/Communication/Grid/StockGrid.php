<?php

namespace SprykerFeature\Zed\Stock\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class StockGrid extends AbstractGrid
{

    const NAME = 'name';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::NAME)
                ->filterable()
                ->sortable()
        ];

        return $plugins;
    }
}
