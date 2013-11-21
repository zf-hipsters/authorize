<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Controller;

use Authorize\Controller\ControllerAbstract;
use Zend\View\Model\ViewModel;

/**
 * Class Authentication
 * @package Authorize\Controller
 */
class Authentication extends ControllerAbstract
{
    /**
     * Login Action
     * @return ViewModel
     */
    public function loginAction()
    {
        $form = $this->getServiceLocator()->get('Authorize\Form\Login');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost();
            $form->setData($postData);
        }

        $returnVars = array(
            'form' => $form,
            'allowRegister' => $this->checkPermission('allowRegister', false),
            'allowForgot' => $this->checkPermission('allowForgotPassword', false),
            'allowRememberMe' => $this->checkPermission('allowRememberMe', false),
        );

        return new ViewModel($returnVars);
    }

    /**
     * Logout Action
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        $authService = $this->getServiceLocator()->get('Authorize\Service\Authentication');
        $authService->logout();

        return $this->redirect()->toRoute('authorize/login');
    }

    /**
     * Authenticate Action
     * @return \Zend\Http\Response
     */
    public function authenticateAction()
    {
        $request = $this->getRequest();

        $form = $this->getServiceLocator()->get('Authorize\Form\Login');
        $redirects = $this->getConfig('redirects');

        if ($request->isPost()) {
            $postData = $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {
                $authService = $this->getServiceLocator()->get('Authorize\Service\Authentication');
                $authResponse = $authService->authenticate($postData);

                if ($authResponse === true) {
                    $this->fm( $this->translate('You have been logged in.') );
                    return $this->redirect()->toRoute($redirects['login_success']);
                } elseif ($authResponse === 'inactive') {
                    $this->fm( $this->translate('Your account has not been activated. Please check your email for activation instructions.'), 'error');
                    return $this->redirect()->toRoute('authorize/login');
                }
            }
        }

        $this->fm( $this->translate('Invalid username and/or password. Please try again.'), 'error');
        return $this->redirect()->toRoute('authorize/login');
    }
}