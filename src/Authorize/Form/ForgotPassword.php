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
use Authorize\Form\Validation\ForgotPassword as InputFilter;

class ForgotPassword extends ProvidesEventsForm
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
            'name' => 'btnSubmit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Reset Password'
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }
}
