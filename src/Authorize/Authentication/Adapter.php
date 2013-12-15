<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Authentication;

use Authorize\Service\ServiceLocatorAware;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container as SessionContainer;

class Adapter extends ServiceLocatorAware implements AdapterInterface
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

        $this->updateIdentity($userObject);

        return $this->getAuthResult(AuthenticationResult::SUCCESS, $userObject->getEmail());
    }

    public function updateIdentity($userObject)
    {
        // regen the id
        $session = new SessionContainer($this->getStorage()->getNameSpace());
        $session->getManager()->regenerateId();

        $storage = $this->getStorage()->read();
        $storage['identity'] = $userObject;
        $this->getStorage()->write($storage);
    }

    /**
     * Called when user id logged out
     * @param  AuthEvent $e event passed
     */
    public function logout()
    {
        $session = new SessionContainer($this->getStorage()->getNameSpace());
        $session->getManager()->destroy();
        $this->getStorage()->forgetMe();

        $storage = $this->getStorage()->read();

        if (isset($storage['identity'])) {
            unset($storage['identity']);
        }
    }

    /**
     * @var \Zend\Authentication\Storage $storage
     * @return bool
     */
    public function userIdentity()
    {
        if ($this->getStorage()->isEmpty()) {
            return false;
        }

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
     * @return mixed
     */
    public function getStorage()
    {
        if (is_null($this->storage)) {
            $this->storage = $this->getServiceLocator()->get('Authorize\Authentication\Storage');
        }

        return $this->storage;
    }
}
