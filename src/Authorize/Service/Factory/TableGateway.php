<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Authorize\Entity\User as Entity;
use Zend\Db\TableGateway\TableGateway as ZendTableGateway;

class TableGateway implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $moduleConfig = $config['zf-hipsters']['authorize'];

        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        $hydrator = new Hydrator;
        $rowObjectPrototype = new Entity;

        $resultSet = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $rowObjectPrototype);

        return $tableGateway = new ZendTableGateway($moduleConfig['user_table'], $dbAdapter, null, $resultSet);
    }
}
