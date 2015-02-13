<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CustomerApi\Controller\CustomerApi' => 'CustomerApi\Controller\CustomerApiController',
        ),
    ),

    // The following section is new` and should be added to your file
    'router' => array(
        'routes' => array(
            'customer-api' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/v1/customers[/:id][/email/:email][/name/:name]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'CustomerApi\Controller\CustomerApi',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);