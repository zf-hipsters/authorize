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
use Zend\View\Model\ViewModel;

use Authorize\Form\Validation\Login as InputFilter;

/**
 * Class Authentication
 * @package Authorize\Controller
 */
class Authentication extends AbstractActionController
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
            'form' => $form
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
        $form->setInputFilter(new InputFilter);

        if ($request->isPost()) {
            $postData = $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {
                $authService = $this->getServiceLocator()->get('Authorize\Service\Authentication');
                if ($authService->authenticate($postData)) {
                    $this->fm('You have been logged in.');
                    return $this->redirect()->toRoute('authorize/login');
                }
            }
        }

        $this->fm('Invalid username and/or password. Please try again.', 'error');
        return $this->redirect()->toRoute('authorize/login');
    }
}