<?php
return array(
    'router' => array(
        'routes' => array(
            'authorize' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'Authorize\Controller\Authentication',
                        'action'     => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'action'     => 'authenticate',
                            ),
                        ),
                    ),
                    'activate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/activate',
                            'defaults' => array(
                                'controller' => 'Authorize\Controller\Account',
                                'action'     => 'activate',
                            ),
                        ),
                    ),
                    'forgot-password' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/forgot-password',
                            'defaults' => array(
                                'controller' => 'Authorize\Controller\Account',
                                'action'     => 'forgot-password',
                            ),
                        ),
                    ),
                    'reset-password' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/reset-password',
                            'defaults' => array(
                                'controller' => 'Authorize\Controller\Account',
                                'action'     => 'reset-password',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);