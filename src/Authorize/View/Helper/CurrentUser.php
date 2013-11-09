<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Users\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CurrentUser
 * @package Users\View\Helper
 */
class CurrentUser extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    public function __invoke()
    {
        if ($this->getAuthService()->userIdentity()) {
            $user = $this->getAuthService()->userIdentity();
        } else {
            return false;
        }

        return $user;
    }

    /**
     * @return array|object|AuthenticationService
     */
    public function getAuthService()
    {
        if (is_null($this->authService)) {
            $this->authService = $this->getServiceLocator()->get('Authorize\Authentication\Adapter');
        }

        return $this->authService;
    }

    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        $sm = $this->serviceLocator->getServiceLocator();
        return $sm;

    }
}
