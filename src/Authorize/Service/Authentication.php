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
 * Class Authentication
 * @package Authorize\Service
 */
class Authentication extends ServiceLocatorAware
{
    protected $mapper;

    /**
     * @param $postData
     * @return bool
     */
    public function authenticate($postData)
    {
        $identity = $postData['identity'];
        $credential = $postData['credential'];
        $userObject = $this->getMapper()->findByEmail($postData['identity']);

        if ($userObject) {
            if ( $userObject->getActive() == 0 ) {
                return 'inactive';
            }

            if ($this->getAuthAdapter($identity, $credential, $userObject)->authenticate()) {
                if ($postData['remember_me'] == 1) {
                    $storage = $this->getServiceLocator()->get('Authorize\Authentication\Storage');
                    $storage->setRememberMe(1);
                }
                return true;
            }
        }

        return false;
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->getAuthAdapter()->logout();
    }

    /**
     * @param null $identity
     * @param null $credential
     * @param null $userObject
     * @return array|object
     */
    public function getAuthAdapter($identity = null, $credential = null, $userObject = null)
    {
        $authAdapter = $this->getServiceLocator()->get('Authorize\Authentication\Adapter');

        $authAdapter
            ->setIdentity($identity)
            ->setCredential($credential)
            ->setUserObject($userObject);

        return $authAdapter;
    }

    /**
     * @param $value
     * @return string
     */
    public function generatePassword($value)
    {
        $bcrypt = new Bcrypt;
        return $bcrypt->setCost(14)->create($value);
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