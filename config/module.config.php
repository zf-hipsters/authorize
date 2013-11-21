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
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'Authorize\Controller\Account',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                    'profile' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/profile',
                            'defaults' => array(
                                'controller' => 'Authorize\Controller\Account',
                                'action'     => 'profile',
                            ),
                        ),
                    ),
                    'activate' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/activate[/:email[/:token]]',
                            'constraints' => array(
                                'token' => '[0-9a-fA-F]*',
                            ),
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
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/reset-password[/:token]',
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
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
