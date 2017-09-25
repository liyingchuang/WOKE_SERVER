<?php
return [
    'statisticsManage' => [
        'action' => 'manage.statistics.index',
        'icon' => 'fa-bar-chart',
        'opened_actions' => [],
        'alias' => '统计管理',
        'child_menus' => []
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
    'groupManage' => [
        'action' => 'manage.group.index',
        'icon' => 'fa-user-plus',
        'alias' => '团购管理',
        'child_menus' => [
            [
                'action' => 'manage.group.index',
                'opened_actions' => [],
                'alias' => '团购商品',
            ],
            [
                'action' => 'manage.group.goods',
                'opened_actions' => [],
                'alias' => '团长信息',
            ],
            [
                'action' => 'manage.group.groupinfo',
                'opened_actions' => [],
                'alias' => '团员信息',
            ],
            [
                'action' => 'manage.group.examine',
                'opened_actions' => [],
                'alias' => '团购审核',
            ],

        ]
    ],
    'financeManage' => [
    'action' => 'manage.finance.index',
    'icon' => 'fa-yen',
    'alias' => '财务统计',
    'child_menus' => [
            [
                'action' => 'manage.finance.index',
                'opened_actions' => [],
                'alias' => '销售查询',
            ],

            [
                'action' => 'manage.finance.index_deta',
                'opened_actions' => [],
                'alias' => '销售排行',
            ],
        ]
    ],
];