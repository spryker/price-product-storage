<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Business;

use Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\ProductSearch\Business\Builder\ProductResourceKeyBuilder;
use Spryker\Zed\ProductSearch\Business\Operation\OperationManager;
use Spryker\Zed\ProductSearch\Business\Locator\OperationLocator;
use Spryker\Zed\ProductSearch\Business\Operation\DefaultOperation;
use Spryker\Zed\ProductSearch\Business\Processor\ProductSearchProcessor;
use Spryker\Zed\ProductSearch\Business\Transformer\ProductAttributesTransformer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Library\Storage\StorageInstanceBuilder;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSearch\Business\Internal\InstallProductSearch;
use Spryker\Zed\ProductSearch\Business\Locator\OperationLocatorInterface;
use Spryker\Zed\ProductSearch\Business\Operation\OperationInterface;
use Spryker\Zed\ProductSearch\Business\Operation\OperationManagerInterface;
use Spryker\Zed\ProductSearch\Business\Processor\ProductSearchProcessorInterface;
use Spryker\Zed\ProductSearch\Business\Transformer\ProductAttributesTransformerInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToCollectorInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\ProductSearch\ProductSearchConfig;
use Spryker\Zed\ProductSearch\Business\Operation\AddToResult;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToFacet;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToField;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToMultiField;
use Spryker\Zed\ProductSearch\ProductSearchDependencyProvider;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer;

/**
 * @method ProductSearchConfig getConfig()
 * @method ProductSearchQueryContainer getQueryContainer()
 */
class ProductSearchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return ProductAttributesTransformerInterface
     */
    public function createProductAttributesTransformer()
    {
        return new ProductAttributesTransformer(
            $this->getProductSearchQueryContainer(),
            $this->createOperationLocator(),
            $this->createDefaultOperation()
        );
    }

    /**
     * @return ProductSearchProcessorInterface
     */
    public function createProductSearchProcessor()
    {
        return new ProductSearchProcessor(
            $this->createKeyBuilder(),
            $this->getStoreName()
        );
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return InstallProductSearch
     */
    public function createInstaller(MessengerInterface $messenger)
    {
        $collectorFacade = $this->getCollectorFacade();

        $installer = new InstallProductSearch(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $collectorFacade->getSearchIndexName(),
            $collectorFacade->getSearchDocumentType()
        );
        $installer->setMessenger($messenger);

        return $installer;
    }

    /**
     * @throws ContainerKeyNotFoundException
     *
     * @return ProductSearchToCollectorInterface
     */
    protected function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return OperationInterface
     */
    protected function createDefaultOperation()
    {
        return new DefaultOperation();
    }

    /**
     * @return OperationLocatorInterface
     */
    protected function createOperationLocator()
    {
        $locator = new OperationLocator();
        $operations = $this->getPossibleOperations();

        foreach ($operations as $operation) {
            $locator->addOperation($operation);
        }

        return $locator;
    }

    /**
     * @return OperationManagerInterface
     */
    protected function createOperationManager()
    {
        return new OperationManager(
            $this->getProductSearchQueryContainer()
        );
    }

    /**
     * @deprecated Use getProductSearchQueryContainer() instead.
     *
     * @return ProductSearchQueryContainerInterface
     */
    protected function createProductSearchQueryContainer()
    {
        trigger_error('Deprecated, use getProductSearchQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getProductSearchQueryContainer();
    }

    /**
     * @return ProductSearchQueryContainerInterface
     */
    protected function getProductSearchQueryContainer()
    {
        return $this->getQueryContainer();
    }

    /**
     * @deprecated Use getLocaleFacade() instead.
     *
     * @return ProductSearchToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        trigger_error('Deprecated, use getLocaleFacade() instead.', E_USER_DEPRECATED);

        return $this->getLocaleFacade();
    }

    /**
     * @return ProductSearchToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @deprecated Use getTouchFacade() instead.
     *
     * @return ProductSearchToTouchInterface
     */
    protected function createTouchFacade()
    {
        trigger_error('Deprecated, use getTouchFacade() instead.', E_USER_DEPRECATED);

        return $this->getTouchFacade();
    }

    /**
     * @return ProductSearchToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return KeyBuilderInterface
     */
    public function createKeyBuilder()
    {
        return new ProductResourceKeyBuilder();
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

    /**
     * @return array|OperationInterface[]
     */
    protected function getPossibleOperations()
    {
        return [
            $this->createAddToResult(),
            $this->createCopyToField(),
            $this->createCopyToFacet(),
            $this->createCopyToMultiField(),
        ];
    }

    /**
     * @return AddToResult
     */
    protected function createAddToResult()
    {
        return new AddToResult();
    }

    /**
     * @return CopyToField
     */
    protected function createCopyToField()
    {
        return new CopyToField();
    }

    /**
     * @return CopyToFacet
     */
    protected function createCopyToFacet()
    {
        return new CopyToFacet();
    }

    /**
     * @return CopyToMultiField
     */
    protected function createCopyToMultiField()
    {
        return new CopyToMultiField();
    }

}