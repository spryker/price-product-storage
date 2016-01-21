<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Client\Cms;

use Spryker\Client\Cms\KeyBuilder\CmsBlockKeyBuilder;
use Spryker\Client\Cms\Storage\CmsBlockStorage;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Cms\Storage\CmsBlockStorageInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class CmsFactory extends AbstractFactory
{

    /**
     * @return CmsBlockStorageInterface
     */
    public function createCmsBlockFinder()
    {
        return new CmsBlockStorage(
            $this->getStorage(),
            $this->createKeyBuilder()
        );
    }

    /**
     * @return StorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::KV_STORAGE);
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new CmsBlockKeyBuilder();
    }

}