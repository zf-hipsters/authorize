<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class Authentication
 * @package Authorize\Controller
 */
abstract class ControllerAbstract extends AbstractActionController
{
    protected $translator;

    public function translate($string)
    {
        if (is_null($this->translator)) {
            $this->translator = $this->getServiceLocator()->get('translator');
        }

        return $this->translator->translate($string);
    }

    public function getConfig($key = null)
    {
        $config = $this->getServiceLocator()->get('Authorize\Service\Factory\Config');

        if (!is_null($key)) {
            if (isset($config[$key])) {
                return $config[$key];
            } else {
                return false;
            }
        }

        return $config;
    }

    public function checkPermission($key, $redirect = true)
    {
        $permisssions = $this->getConfig('permissions');
        if (isset($permisssions[$key]) && $permisssions[$key] == true) {
            return true;
        }

        if ($redirect === true) {
            $response = $this->getResponse();
            $response->setStatusCode('404');
            return $response;
        }

        return false;
    }
}