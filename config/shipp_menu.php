<?php
return [
    'orderManage' => [
        'action' => 'manage.order.index',
        'icon' => 'fa-pencil',
        'alias' => '订单管理',
        'child_menus' => [
            [
                'action' => 'manage.order.index',
                'opened_actions' => ['manage.order.index_list_view'],
                'alias' => '订单列表'
            ],
            /*[
                'action' => 'manage.order.supplier_list_view',
                'opened_actions' => ['manage.order.supplier_list_view'],
                'alias' => '入驻商订单列表'
            ],*/
            [
                'action' => 'manage.order.home',
                'opened_actions' => ['manage.order.delivery_list_view'],
                'alias' => '发货单列表'
            ],
           /*, [
                'action' => 'manage.order.back_list',
                'opened_actions' => ['manage.order.back_list_view'],
                'alias' => '退货/款及维修'
            ]
            [
                'action' => 'manage.order.supplier_back_list',
                'opened_actions' => ['manage.order.supplier_back_list_view'],
                'alias' => '第三方退货/款及维修'
            ]*/
        ]
    ],
];