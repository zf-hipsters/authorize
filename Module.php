<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize;

use Zend\EventManager\EventInterface;

class Module
{
    public function onBootstrap(EventInterface $e)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $e->getApplication();
        $eventManager = $app->getEventManager();
        $sm = $app->getServiceManager();

        $config = $sm->get('Config');

        $eventManager->attach('route', function($e) use($config, $app) {
            if (!isset($config['zf-hipsters']['authorize'])) {
                $this->redirect($e, 'authorize_install');
            }

            if (isset($config['zf-hipsters']['authorize'])
                && $config['zf-hipsters']['authorize']['permissions']['redirectOn403'] == true
                && $config['zf-hipsters']['authorize']['permissions']['enableAcl'] == true
                && $app->getMvcEvent()->getRouteMatch()->getMatchedRouteName() != 'authorize_install')
            {
                // attach dispatch listener

                $app = $e->getApplication();
                $sm = $app->getServiceManager();
                $rbac = $sm->get('ZfcRbac\Service\Rbac');

                $route = $app->getMvcEvent()->getRouteMatch()->getMatchedRouteName();

                if ($rbac->getFirewall('route')->isGranted($route)) {
                    return true;
                }

                $this->redirect($e, 'authorize/login');
            }
        });
    }

    protected function redirect(EventInterface $e, $route)
    {
        $app = $e->getApplication();
        $sm = $app->getServiceManager();

        /** @var \Zend\Mvc\Router\Http\TreeRouteStack $route */
        $currentRoute = $app->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        if ($currentRoute == $route) {
            return false;
        }

        $matchedRoute = $sm->get('Router')->assemble(array(), array('name'=>$route));

        $response = $e->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $matchedRoute);
        $response->setStatusCode(302);
        $response->sendHeaders();

        $e->stopPropagation();
        return false;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'currentUser' => 'Authorize\View\Helper\CurrentUser',
            ),
        );
    }

    public function getControllerPluginConfig()
    {
        return array(
            'invokables' => array(
                'currentUser' => 'Authorize\Controller\Plugin\CurrentUser',
            )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
