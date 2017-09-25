<?php
return [
    'userManage' => [
        'action' => 'manage.user.index',
        'icon' => 'fa-users',
        'opened_actions' => [],
        'alias' => '会员管理',
        'child_menus' => [
            [
                'action' => 'manage.user.index',
                'alias' => '会员管理',
                'opened_actions' => ['manage.user.edit','manage.user.integral'],
            ],
            /*,
             [
                'action' => 'manage.user.banned',
                'alias' => '禁言会员',
                'opened_actions' => [],  
            ]
            ,
            [
                'action' => 'manage.user.report',
                'alias' => '投诉会员',
                'opened_actions' => [],
            ]*/
        ]
    ],
    'goodsManage' => [
        'action' => 'manage.goods.index',
        'icon' => 'fa-th',
        'alias' => '商品管理',
        'child_menus' => [
            [
                'action' => 'manage.goods.index',
                'opened_actions' => ['manage.goods.show','manage.goods.goods_update'],
                'alias' => '商品列表'
            ],

            [
                'action' => 'manage.goods.recycle',
                'opened_actions' => [],
                'alias' => '商品回收站'
            ]
        ]
    ],
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
    'transferManage' => [
        'action' => 'manage.transfer.index',
        'icon' => 'fa-money',
        'alias' => '提现管理',
        'child_menus' => []
    ],
];