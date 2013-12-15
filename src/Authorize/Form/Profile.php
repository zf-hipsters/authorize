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
use Authorize\Form\Validation\Profile as InputFilter;

class Profile extends ProvidesEventsForm
{
    public function __construct($name = null)
    {
        $this->setInputFilter(new InputFilter());
        parent::__construct($name);

        $this->add(array(
            'name' => 'first_name',
            'options' => array(
                'label' => 'First Name*:',
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'First Name',
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'last_name',
            'options' => array(
                'label' => 'Last Name*:',
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Last Name',
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email*:',
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Email Address',
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password*:',
            ),
            'attributes' => array(
                'type' => 'password',
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'confirm',
            'options' => array(
                'label' => 'Confirm Password*:',
            ),
            'attributes' => array(
                'type' => 'password',
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'setPassword',
            'attributes' => array(
                'type' => 'button',
                'value' => 'Change Password'
            ),
        ));

        $this->add(array(
            'name' => 'btnSubmit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Update Profile'
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }
}
