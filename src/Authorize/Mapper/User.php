<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Mapper;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class User
 * @package Authorize\Mapper
 */
class User
{
    protected $hydrator = null;
    protected $entity = null;
    protected $tableGateway = null;

    public function register($postVars)
    {
        $entity = $this->getEntity();
        $this->getHydrator()->hydrate($postVars, $entity);

        $entity->setCreated(date('Y-m-d H:i:s'));
        $insert = $this->getHydrator()->extract($entity);

        unset($insert['user_id']);

        $this->getTableGateway()->insert($insert);

        return $this->findByEmail($postVars['email']);
    }

    public function updateProfile($postvars, $id)
    {
        if (isset($postvars['id'])) {
            unset($postvars['id']);
        }

        $this->getTableGateway()->update($postvars, array('id'=>$id));
    }

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

    public function findByToken($token)
    {

        $results = $this->getTableGateway()->select(function(Select $select) use ($token) {
            $select->where->like('reset_token', $token . '|%');
        });

        if (! empty($results)) {
            return $results->current();
        }

        return false;
    }

    public function setToken($token, $id)
    {
        $this->getTableGateway()->update(array('reset_token'=>$token), array('id'=>$id));
    }

    public function activate($id)
    {
        $this->getTableGateway()->update(array('active'=>1), array('id'=>$id));
    }

    public function setPassword($password, $id)
    {
        $this->getTableGateway()->update(array('password'=>$password), array('id'=>$id));
    }

    /**
     * @param null $tableGateway
     * @return TableGateway
     */
    public function setTableGateway($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return \Zend\Db\TableGateway\TableGateway
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
