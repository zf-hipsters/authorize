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

class Account extends ControllerAbstract
{
    public function registerAction()
    {
        $this->checkPermission('allowProfileUpdate');

        $form = $this->getServiceLocator()->get('Authorize\Form\Register');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {
                $accountService = $this->getServiceLocator()->get('Authorize\Service\Account');
                if ($accountService->register($form->getData())) {
                    $this->fm( $this->translate('A confirmation email has been sent to your email address. Please click the activation link in the email to proceed.') );
                    return $this->redirect()->toRoute('authorize/login');
                }

                $this->fm( $this->translate('The email address is already registered in the system.'), 'error' );
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function activateAction()
    {
        $email = urldecode($this->params()->fromRoute('email'));
        $token = $this->params()->fromRoute('token');

        if (empty($email) || empty($token)) {
            $this->fm( $this->translate('The activation link you specified is invalid. Please click the email link again.'), 'error' );
            return $this->redirect()->toRoute('authorize/login');
        }

        $accountService = $this->getServiceLocator()->get('Authorize\Service\Account');
        if ($accountService->activate($email, $token)) {
            $this->fm( $this->translate('Your account was successfully activated. Please login below.'));
            return $this->redirect()->toRoute('authorize/login');
        }

        $this->fm( $this->translate('Your account could not be activated.'));
        return $this->redirect()->toRoute('authorize/login');

    }

    public function forgotPasswordAction()
    {
        $this->checkPermission('allowForgotPassword');

        $request = $this->getRequest();

        $form = $this->getServiceLocator()->get('Authorize\Form\ForgotPassword');

        if ($request->isPost()) {
            $postData = $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {
                $accountService = $this->getServiceLocator()->get('Authorize\Service\Account');
                if ($accountService->forgotPassword($form->getData())) {
                    $this->fm( $this->translate('A password reset notice has been sent to your registered email address.') );
                    return $this->redirect()->toRoute('authorize/login');
                }
            }

            $this->fm( $this->translate('The email address specified was not found in the system.'), 'error');
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function resetPasswordAction()
    {
        $request = $this->getRequest();
        $form = $this->getServiceLocator()->get('Authorize\Form\ResetPassword');
        $accountService = $this->getServiceLocator()->get('Authorize\Service\Account');
        $token = $this->params()->fromRoute('token');

        if (! $token || ! $user = $accountService->checkToken($token)) {
            $this->fm( $this->translate('The security token is invalid or has expired. Please try again.') );
            return $this->redirect()->toRoute('authorize/forgot-password');
        }

        if ($request->isPost()) {
            $postData = $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {
                if ($accountService->resetPassword($form->getData(), $user)) {
                    $this->fm( $this->translate('Your password has been reset, please login below.') );
                    return $this->redirect()->toRoute('authorize/login');
                }
            }

            $this->fm( $this->translate('There were some issues with your form.'), 'error');
        }

        return new ViewModel(array(
            'form' => $form,
            'token' => $token,
        ));
    }

    public function profileAction()
    {
        $this->checkPermission('allowProfileUpdate');

        $accountService = $this->getServiceLocator()->get('Authorize\Service\Account');

        $form = $this->getServiceLocator()->get('Authorize\Form\Profile');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost();

            if ($postData['password'] == '' && $postData['confirm'] == '') {
                $this->removePasswordInputFilter($form);
            }

            $form->setData($postData);

            if ($form->isValid()) {
                $accountService->updateProfile($form->getData());
                $this->fm( $this->translate('Your profile was successfully updated.'));
            } else {
                $this->fm( $this->translate('There were some issues with your form.'), 'error');
            }

        } else {
            $profileData = $accountService->getProfile($this->currentUser()->getEmail());

            $form->setData($profileData);
            $this->removePasswordInputFilter($form);
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function removePasswordInputFilter(&$form)
    {
        $form->get('password')->setValue('')->removeAttribute('required');
        $form->get('confirm')->setValue('')->removeAttribute('required');
        $form->getInputFilter()->remove('password');
        $form->getInputFilter()->remove('confirm');
    }
}
