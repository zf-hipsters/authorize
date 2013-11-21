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

        if (!isset($config['mail'])) {
            throw new \Exception('Mail configuration not found. Please copy email.global.php.dist to your /config/autoload folder and rename to email.global.php');
        }

        if ($config['zf-hipsters']['authorize']['permissions']['redirectOn403'] == true
            && $config['zf-hipsters']['authorize']['permissions']['enableAcl'] == true) {
            // attach dispatch listener
            $eventManager->attach('route', function($e) {
                /** @var \Zend\Mvc\Application $app */
                $app = $e->getApplication();
                $sm = $app->getServiceManager();
                $rbac = $sm->get('ZfcRbac\Service\Rbac');

                $route = $app->getMvcEvent()->getRouteMatch()->getMatchedRouteName();

                if ($rbac->getFirewall('route')->isGranted($route)) {
                    return true;
                }

                $matchedRoute = $sm->get('Router')->assemble(array(), array('name'=>'authorize/login'));

                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $matchedRoute);
                $response->setStatusCode(302);
                $response->sendHeaders();

                $e->stopPropagation();
                return false;

            });
        }
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
