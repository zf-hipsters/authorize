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
class ForgotPassword extends ProvidesEventsInputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'identity',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter the users\'s email address'
                        ),
                    ),
                ),

            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }
}
