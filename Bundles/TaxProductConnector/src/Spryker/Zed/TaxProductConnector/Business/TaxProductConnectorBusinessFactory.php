<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Business;

use Spryker\Zed\TaxProductConnector\Business\Plugin\TaxChangeTouchPlugin;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorConfig;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface;
use Spryker\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorDependencyProvider;

/**
 * @method TaxProductConnectorConfig getConfig()
 * @method TaxProductConnectorQueryContainer getQueryContainer()
 */
class TaxProductConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return TaxChangePluginInterface
     */
    public function createTaxChangeTouchPlugin()
    {
        return new TaxChangeTouchPlugin(
            $this->getProductFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return TaxProductConnectorToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(TaxProductConnectorDependencyProvider::FACADE_PRODUCT);
    }

}