<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class CurrentUser extends AbstractPlugin
{
    protected $authService;

    public function __invoke()
    {
        $authService = $this->getController()->getServiceLocator()->get('Authorize\Authentication\Adapter');
        return $authService->userIdentity();
    }
}