<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Service;

use Zend\Crypt\Password\Bcrypt;

/**
 * Class Account
 * @package Authorize\Service
 */
class Account extends ServiceLocatorAware
{
    protected $mapper;

    public function getProfile($email)
    {
        $user = $this->getMapper()->findByEmail($email, true);
        return  $user;
    }

    public function updateProfile($postVars)
    {
        $user = $this->getMapper()->findByEmail($postVars['email']);
        if (empty($user)) {
            return false;
        }

        $authService = $this->getServiceLocator()->get('Authorize\Service\Authentication');

        if ((isset($postVars['password']) && $postVars['password'] != '')
            && (isset($postVars['confirm']) && $postVars['confirm'] != '')) {
            $postVars['password'] = $authService->generatePassword($postVars['confirm']);
        }

        $this->getMapper()->getHydrator()->hydrate($postVars, $user);
        $updateArray = $this->getMapper()->getHydrator()->extract($user);

        $this->getMapper()->updateProfile($updateArray, $user->getId());

        $userObject = $this->getMapper()->findByEmail($postVars['email']);
        $authService->getAuthAdapter()->updateIdentity($userObject);

        return true;
    }

    public function resetPassword($postVars, $user)
    {
        $authService = $this->getServiceLocator()->get('Authorize\Service\Authentication');
        $password = $authService->generatePassword($postVars['confirm']);

        $this->getMapper()->setPassword($password, $user->getId());
        $this->getMapper()->setToken('', $user->getId());

        return true;
    }

    public function register($postVars)
    {
        $config = $this->getServiceLocator()->get('Config');
        $authConfig = $this->getServiceLocator()->get('Authorize\Service\Factory\Config');
        if (! isset($postVars['email']) || $postVars['password'] == '') {
            throw new \Exception('Email and password were not passed to the service.');
        }

        $user = $this->getMapper()->findByEmail($postVars['email']);
        if (! empty($user)) {
            return false;
        }

        $authService = $this->getServiceLocator()->get('Authorize\Service\Authentication');
        $postVars['password'] = $authService->generatePassword($postVars['confirm']);

        $userObject = $this->getMapper()->register($postVars);

        if ($authConfig['permissions']['requireActivation'] === true) {
            $activation_token = sha1($userObject->getEmail() . $userObject->getId() . $authConfig['salt']);
            $transport = $this->getServiceLocator()->get('Authorize\Service\Email');
            $transport
                ->from($config['mail']['from'])
                ->to($userObject->getEmail())
                ->subject('Account Activation')
                ->body('activation', array(
                    'email' => $userObject->getEmail(),
                    'first_name' => $userObject->getFirstname(),
                    'token' => $activation_token,
                ));

            $transport->send();
        } else {
            $this->getMapper()->activate($userObject->getId());
        }

        return true;
    }

    public function activate($email, $token)
    {
        if ($email == '' || $token == '') {
            throw new \Exception('Email and token were not passed to the service.');
        }

        $user = $this->getMapper()->findByEmail($email);
        $authConfig = $this->getServiceLocator()->get('Authorize\Service\Factory\Config');

        if (empty($user)) {
            return false;
        }

        $activation_token = sha1($user->getEmail() . $user->getId() . $authConfig['salt']);

        if ($token !== $activation_token) {
            return false;
        }

        $this->getMapper()->activate($user->getId());
        return true;
    }

    public function forgotPassword($postVars)
    {
        $config = $this->getServiceLocator()->get('Config');
        if (! isset($postVars['identity']) || $postVars['identity'] == '') {
            return false;
        }

        $user = $this->getMapper()->findByEmail($postVars['identity']);

        if (empty($user)) {
            return false;
        }

        $reset_token = uniqid();
        $this->getMapper()->setToken($reset_token . '|' . strtotime('+2 hours'), $user->getId());

        $transport = $this->getServiceLocator()->get('Authorize\Service\Email');
        $transport
            ->from($config['mail']['from'])
            ->to($user->getEmail())
            ->subject('Reset Password Request')
            ->body('forgot-password', array(
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'token' => $reset_token
        ));

        $transport->send();

        return true;
    }

    public function checkToken($token)
    {
        if (! $user = $this->getMapper()->findByToken($token)) {
            return false;
        }

        $tokenFull = $user->getResetToken();
        $exp = explode('|', $tokenFull);

        $expiry = $exp[1];

        if (time() > $expiry) {
            return false;
        }

        return $user;
    }

    /**
     * @return array|object
     */
    public function getMapper()
    {
        if (is_null($this->mapper)) {
            $this->mapper = $this->getServiceLocator()->get('Authorize\Service\Factory\UserMapper');
        }

        return $this->mapper;
    }
}
