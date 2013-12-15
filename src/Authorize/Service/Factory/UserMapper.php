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

class UserMapper implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mapper = $serviceLocator->get('Authorize\Mapper\User');
        $mapper->setHydrator(new Hydrator);
        $mapper->setEntity(new Entity);
        $mapper->setTableGateway($serviceLocator->get('Authorize\Service\Factory\TableGateway'));
        return $mapper;
    }
}
