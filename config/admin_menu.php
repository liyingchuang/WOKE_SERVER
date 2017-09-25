<?php
return [
    'statisticsManage' => [
        'action' => 'manage.statistics.index',
        'icon' => 'fa-bar-chart',
        'opened_actions' => [],
        'alias' => '统计管理',
        'child_menus' => []
    ],
    'rbacManage' => [
        'action' => 'manage.rbac.index',
        'icon' => 'fa-user',
        'opened_actions' => ['manage.rbac.show','manage.rbac.users'],
        'alias' => '角色管理',
        'child_menus' => []
    ],
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
    'adsManage' => [
        'action' => 'manage.adCategory.index',
        'icon' => 'fa-picture-o',
        'alias' => '广告管理',
        'child_menus' => [
            [
                'action' => 'manage.adCategory.index',
                'alias' => '广告管理',
                'opened_actions' => ['manage.ads.index'],  
            ]
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
            ],
            [
                'action' =>'manage.goods.istrator',
                'opened_actions' => [],
                'alias' => '第三方商品管理'
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
    'makterManage' => [
        'action' => 'manage.makter.index',
        'icon' => 'fa-pie-chart',
        'alias' => '猜大盘统计',
        'child_menus' => []
    ],
    'transferManage' => [
        'action' => 'manage.transfer.index',
        'icon' => 'fa-money',
        'alias' => '提现管理',
        'child_menus' => []
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
    'storeManage' => [
        'action' => 'manage.store.index',
        'icon' => 'fa-bank',
        'alias' => '店铺管理',
        'opened_actions' => ['manage.store.show'],
        'child_menus' => []
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
    'shippManage' =>[
        'action' => 'manage.shipp.index',
        'icon' => 'fa-truck',
        'alias' => '运费模板',
        'child_menus' => [
            [
                'action' => 'manage.shipp.index',
                'opened_actions' => [],
                'alias' => '运费模板',
            ],

        ],
    ],
    'versionManage' => [
        'action' => 'manage.version.ios',
        'icon' => 'fa-cogs',
        'alias' => '版本控制',
        'child_menus' => [
            [
                'action' => 'manage.version.ios',
                'opened_actions' => [],
                'alias' => 'iOS',
            ],
            [
                'action' => 'manage.version.android',
                'opened_actions' => [],
                'alias' => 'Android',
            ],
        ],

    ],
];