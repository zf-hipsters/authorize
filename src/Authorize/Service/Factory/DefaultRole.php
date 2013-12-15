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

class DefaultRole implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator->get('Authorize\Authentication\Adapter')->userIdentity()) {
            return $serviceLocator->get('Authorize\Authentication\Adapter')->userIdentity()->getRole();
        }

        return 'guest';
    }
}
