<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Form;

use ZfcBase\Form\ProvidesEventsForm;
use Authorize\Form\Validation\Login as InputFilter;

class Login extends ProvidesEventsForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setInputFilter(new InputFilter());

        $this->add(array(
            'name' => 'identity',
            'options' => array(
                'label' => 'Email Address:',
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Email',
            ),
        ));

        $this->add(array(
            'name' => 'credential',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'type' => 'password',
                'placeholder' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'remember_me',
            'options' => array(
                'label' => 'Remember Me?',
            ),
            'type' => 'checkbox',
            'attributes' => array(
                'value' => 'remember_me'
            ),
        ));

        $this->add(array(
            'name' => 'btnSubmit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Login'
            ),
        ));

        $this->add(array(
            'name' => 'btnRegister',
            'attributes' => array(
                'type' => 'register',
                'value' => 'Register'
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }
}
