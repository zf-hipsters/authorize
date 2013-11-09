<?php

return array(
    'zf-hipsters' => array(
        'authorize' => array(
            'user_table' => 'users',
            'redirects' => array(
                'login_success' => 'home',
                'login_fail' => 'user/login',
            )
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'currentUser' => 'Authorize\View\Helper\CurrentUser',
        )
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'currentUser' => 'Authorize\Controller\Plugin\CurrentUser',
        )
    ),
    'view_manager' => array(
        'not_found_template'       => 'error/404',
        'template_map' => array(
            'layout/login-layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/403'               => __DIR__ . '/../view/error/404.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
