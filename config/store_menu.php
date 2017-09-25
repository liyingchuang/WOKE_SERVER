<?php
return [/*
    'goodsManage' => [
        'action' => 'manage.goods.index',
        'icon' => 'fa-th',
        'alias' => '商品管理',
        'child_menus' => [
            [
                'action' => 'manage.goods.index',
                'opened_actions' => ['manage.goods.show','manage.goods.goods_update'],
                'alias' => '审核通过商品'
            ],
            [
                'action' => 'manage.goods.supplier_status',
                'opened_actions' => [],
                'alias' => '审核期间商品'
            ],
            [
                'action' => 'manage.goods.shop_category',
                'opened_actions' => [],
                'alias' => '店内分类'
            ],
            [
                'action' => 'manage.goods.recycle_bin',
                'opened_actions' => [],
                'alias' => '商品回收站'
            ]
        ]
    ],*//*
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
            [
                'action' => 'manage.order.show',
                'opened_actions' => ['manage.order.delivery_list_view'],
                'alias' => '发货单列表'
            ],
            [
                'action' => 'manage.order.back_list',
                'opened_actions' => [],
                'alias' => '退货/款及维修'
            ]
        ]
    ],*//*
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
                'action' => 'manage.goods.supplier_status',
                'opened_actions' => [],
                'alias' => '商品状态'
            ],
            [
                'action' => 'manage.goods.shop_category',
                'opened_actions' => [],
                'alias' => '店内分类管理'
            ],
            [
                'action' => 'manage.goods.recycle_bin',
                'opened_actions' => [],
                'alias' => '商品回收站'
            ]
        ]
    ],*//*
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
            [
                'action' => 'manage.order.show',
                'opened_actions' => ['manage.order.delivery_list_view'],
                'alias' => '发货单列表'
            ],
            [
                'action' => 'manage.order.back_list',
                'opened_actions' => ['manage.order.back_list_view'],
                'alias' => '退货/款及维修'
            ]
        ]
    ],*/
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
//    'storeManage' => [
//        'action' => 'manage.store.show',
//        'icon' => 'fa-bank',
//        'alias' => '店铺管理',
//        'child_menus' => []
//    ],
];

