<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Dependency\QueryContainer;

use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class CustomerApiToApiBridge implements CustomerApiToApiInterface
{

    /**
     * @var \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface $apiQueryContainer
     */
    public function __construct($apiQueryContainer)
    {
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $criteriaTransfer)
    {
        return $this->apiQueryContainer->createQuery($query, $criteriaTransfer);
    }

    /**
     * @param string $json
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    public function createPropelQueryBuilderCriteriaFromJson($json)
    {
        return $this->apiQueryContainer->createPropelQueryBuilderCriteriaFromJson($json);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapPagination(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer)
    {
        return $this->apiQueryContainer->mapPagination($query, $apiPaginationTransfer);
    }

    /**
     * @param string $tableName
     * @param array $tableFields
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $allowedFields
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapFields($tableName, array $tableFields, ModelCriteria $query, array $allowedFields)
    {
        return $this->apiQueryContainer->mapFields($tableName, $tableFields, $query, $allowedFields);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollection(array $data)
    {
        return $this->apiQueryContainer->createApiCollection($data);
    }

    /**
     * @param array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param int|null $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem($data, $id = null)
    {
        return $this->apiQueryContainer->createApiItem($data, $id);
    }

}