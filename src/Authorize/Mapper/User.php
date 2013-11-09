<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Mapper;

/**
 * Class User
 * @package Authorize\Mapper
 */
class User
{
    protected $hydrator = null;
    protected $entity = null;
    protected $tableGateway = null;

    /**
     * Find user by email address
     * @param $id
     * @param bool $returnArray
     * @return mixed
     */
    public function findByEmail($id, $returnArray = false)
    {
        $results = $this->getTableGateway()->select(array('email' => $id));

        if ($returnArray) {
            $record = $results->current();
            $update = $this->getHydrator()->extract($record);

            return $update;
        }

        return $results->current();
    }

    /**
     * @param null $tableGateway
     */
    public function setTableGateway($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return null
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * @param null $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param null $hydrator
     */
    public function setHydrator($hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @return null
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }



}