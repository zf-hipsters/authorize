<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace Authorize\Form\Validation;

use ZfcBase\InputFilter\ProvidesEventsInputFilter;

/**
 * Class Login
 * @package Authorize\Form\Validation
 */
class Register extends ProvidesEventsInputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'first_name',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter your first name'
                        ),
                    ),
                ),

            ),
        ));

        $this->add(array(
            'name'       => 'last_name',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter your last name'
                        ),
                    ),
                ),

            ),
        ));

        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter your last name'
                        ),
                    ),
                ),
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\EmailAddress::INVALID => 'You must enter a valid email address'
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'password',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter a password'
                        ),
                    ),
                ),
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_SHORT => 'The password must be at least 6 characters'
                        ),
                    ),
                ),

            ),
        ));

        $this->add(array(
            'name'       => 'password',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter a password'
                        ),
                    ),
                ),
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_SHORT => 'The password must be at least 6 characters'
                        ),
                    ),
                ),
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'confirm',
                        'messages' => array(
                            \Zend\Validator\Identical::NOT_SAME => 'The password and confirm must be identical'
                        ),
                    ),
                ),

            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }
}
