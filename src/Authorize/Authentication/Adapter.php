<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Authentication;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container as SessionContainer;

class Adapter implements AdapterInterface
{
    protected $identity;
    protected $credential;
    protected $userObject;
    protected $storage;

	/**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     *               If authentication cannot be performed
     */
    public function authenticate()
    {
        $credential = $this->getCredential();
        $identity = $this->getIdentity();
        $userObject = $this->getUserObject();

        $bcrypt = new Bcrypt();
        $bcrypt->setCost(14);

        if (!$bcrypt->verify($this->getCredential(),$userObject->getPassword())) {
            // Password does not match
            return false;
        }

        // regen the id
        $session = new SessionContainer($this->getStorage()->getNameSpace());
        $session->getManager()->regenerateId();

        $storage = $this->getStorage()->read();
        $storage['identity'] = $userObject;
        $this->getStorage()->write($storage);

        return $this->getAuthResult(AuthenticationResult::SUCCESS, $userObject->getEmail());
    }

    /**
     * Called when user id logged out
     * @param  AuthEvent $e event passed
     */
    public function logout()
    {
        $session = new SessionContainer($this->getStorage()->getNameSpace());
        $session->getManager()->destroy();

        $storage = $this->getStorage()->read();

        if (isset($storage['identity'])) {
            unset($storage['identity']);
        }
    }

    public function userIdentity()
    {
        $storage = $this->getStorage()->read();

        if ($storage['identity']) {
            return $storage['identity'];
        }

        return false;
    }

    protected function getAuthResult($result = AuthenticationResult::FAILURE, $identity = null)
    {
        return new AuthenticationResult(
            $result, $identity
        );
    }

    /**
     * @param mixed $credential
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * @param mixed $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param mixed $userObject
     */
    public function setUserObject($userObject)
    {
        $this->userObject = $userObject;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserObject()
    {
        return $this->userObject;
    }

    /**
     * @param mixed $storage
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStorage()
    {
        return $this->storage;
    }


}