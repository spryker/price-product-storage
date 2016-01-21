<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCmsInterface;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleInterface;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductInterface;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToTouchInterface;
use Spryker\Zed\ProductCategory\ProductCategoryDependencyProvider;
use Spryker\Zed\ProductCategory\ProductCategoryConfig;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;

/**
 * @method ProductCategoryConfig getConfig()
 * @method ProductCategoryQueryContainer getQueryContainer()
 */
class ProductCategoryBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return ProductCategoryManagerInterface
     */
    public function createProductCategoryManager()
    {
        return new ProductCategoryManager(
            $this->getCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getCategoryFacade(),
            $this->getTouchFacade(),
            $this->getCmsFacade(),
            $this->getProvidedDependency(ProductCategoryDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @deprecated Use getCategoryQueryContainer() instead.
     *
     * @return CategoryQueryContainerInterface
     */
    protected function createCategoryQueryContainer()
    {
        trigger_error('Deprecated, use getCategoryQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getCategoryQueryContainer();
    }

    /**
     * @return CategoryQueryContainerInterface
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::CATEGORY_QUERY_CONTAINER);
    }

    /**
     * @deprecated Use getLocaleFacade() instead.
     *
     * @return ProductCategoryToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        trigger_error('Deprecated, use getLocaleFacade() instead.', E_USER_DEPRECATED);

        return $this->getLocaleFacade();
    }

    /**
     * @return ProductCategoryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @deprecated Use getProductFacade() instead.
     *
     * @return ProductCategoryToProductInterface
     */
    protected function createProductFacade()
    {
        trigger_error('Deprecated, use getProductFacade() instead.', E_USER_DEPRECATED);

        return $this->getProductFacade();
    }

    /**
     * @return ProductCategoryToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @deprecated Use getCategoryFacade() instead.
     *
     * @return ProductCategoryToCategoryInterface
     */
    protected function createCategoryFacade()
    {
        trigger_error('Deprecated, use getCategoryFacade() instead.', E_USER_DEPRECATED);

        return $this->getCategoryFacade();
    }

    /**
     * @return ProductCategoryToCategoryInterface
     */
    protected function getCategoryFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @deprecated Use getTouchFacade() instead.
     *
     * @return ProductCategoryToTouchInterface
     */
    protected function createTouchFacade()
    {
        trigger_error('Deprecated, use getTouchFacade() instead.', E_USER_DEPRECATED);

        return $this->getTouchFacade();
    }

    /**
     * @return ProductCategoryToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @deprecated Use getCmsFacade() instead.
     *
     * @return ProductCategoryToCmsInterface
     */
    protected function createCmsFacade()
    {
        trigger_error('Deprecated, use getCmsFacade() instead.', E_USER_DEPRECATED);

        return $this->getCmsFacade();
    }

    /**
     * TODO: https://spryker.atlassian.net/browse/CD-540
     *
     * @return ProductCategoryToCmsInterface
     */
    protected function getCmsFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CMS);
    }

    /**
     * @return TransferGeneratorInterface
     */
    public function createProductCategoryTransferGenerator()
    {
        return new TransferGenerator();
    }

}