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

class Form implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this;
    }

    public function get($namespace, $form)
    {

        $formNamespace = sprintf('%s\%s', $namespace, $form);
        $filterNamespace = sprintf('%s\Validation\%s', $namespace, $form);

        $form = new $formNamespace;

        if (class_exists($filterNamespace)) {
            $form->setInputFilter(new $filterNamespace);
        }

        return $form;

    }
}