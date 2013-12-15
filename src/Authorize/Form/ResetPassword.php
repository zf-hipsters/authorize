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
use Authorize\Form\Validation\ResetPassword as InputFilter;

class ResetPassword extends ProvidesEventsForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setInputFilter(new InputFilter());

        $this->add(array(
            'name' => 'credential',
            'attributes' => array(
                'type' => 'password',
                'placeholder' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'confirm',
            'attributes' => array(
                'type' => 'password',
                'placeholder' => 'Confirm Password',
            ),
        ));

        $this->add(array(
            'name' => 'btnSubmit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Login'
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }
}
