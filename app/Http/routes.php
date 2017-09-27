<?php

/*
  |--------------------------------------------------------------------------
  | manage Routes
  |--------------------------------------------------------------------------
  |
  |
 */
Route::get('/', function () {
    return redirect("manage/login");
});
Route::get('manage/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('manage/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('manage/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
Route::group(['prefix' => 'manage', 'namespace' => 'manage', 'middleware' => 'ManageAuth'], function () {
    Route::resource('upload', 'UploadController', ['only' => ['store']]); //公共文件上传
    Route::resource('home', 'HomeController', ['only' => ['index', 'show']]); //登陆入口
    Route::post('user/save', ['as' => 'manage.user.save', 'uses' => 'UserController@postSave']); //用户表
    Route::resource('user', 'UserController', ['only' => ['index', 'store', 'create', 'edit', 'show', 'destroy']]); //用户相关
    Route::get('user/subor/{id}', ['as' => 'manage.user.subor', 'uses' => 'UserController@subor']);//下级用户
    Route::get('user/thewine/{id}', ['as' => 'manage.user.thewine', 'uses' => 'UserController@thewine']);//酒币明细
    Route::resource('ads', 'AdvertisementController', ['only' => ['index', 'store', 'create', 'edit']]); //广告相关
    Route::resource('adCategory', 'AdvertisementCategoryController', ['only' => ['index', 'store']]); //广告分类相关
    Route::controller('rbac', 'RbacController', ['getIndex'=>'manage.rbac.index' , 'getShow'=>'manage.rbac.show', 'getUsers'=>'manage.rbac.users', 'postAddadmin'=>'manage.rbac.addadmin', 'getAdmindel'=>'manage.rbac.admindel', 'getCreate'=>'manage.rbac.create']); //Rbac.
    //商品相关
    Route::get('goods/recycle', ['as' => 'manage.goods.recycle', 'uses' => 'GoodsController@recycle']);
    Route::post('goods/updateNumber', ['as' => 'manage.goods.updatenumber', 'uses' => 'GoodsController@updateNumber']);
    Route::get('goods/updateStatus', ['as' => 'manage.goods.updatestatus', 'uses' => 'GoodsController@updateStatus']);
    Route::post('goods/save', ['as' => 'manage.goods.save', 'uses' => 'GoodsController@save']);
    Route::any('goods/istrator', ['as' => 'manage.goods.istrator', 'uses' => 'GoodsController@istrator']);
    Route::get('goods/look/{id}', ['as' => 'manage.goods.look', 'uses' => 'GoodsController@look']);
    Route::resource('goods', 'GoodsController', ['only' => ['index', 'create', 'store', 'edit']]);
    Route::get('statistics', ['as' => 'manage.statistics.index', 'uses' => 'StatisticsController@index']); //统计管理
    //提现
    Route::resource('transfer', 'TransferController', ['only' => ['index', 'show']]);
    //猜大盘
    Route::resource('makter', 'MakterController', ['only' => ['index']]);
    //订单管理
    Route::get('order/home', ['as' => 'manage.order.home', 'uses' => 'OrderController@home']);
    Route::get('order/export', ['as' => 'manage.order.export', 'uses' => 'OrderController@export']);
    Route::get('order/exportorder', ['as' => 'manage.order.exportorder', 'uses' => 'OrderController@exportorder']);
    Route::resource('order', 'OrderController', ['only' => ['index', 'show', 'store']]);
    //财务统计
    Route::get('finance', ['as' => 'manage.finance.index', 'uses' => 'FinanceController@index']);
    Route::get('finance/index_deta', ['as' => 'manage.finance.index_deta', 'uses' => 'FinanceController@index_deta']);
    Route::get('finance/deta_excel', ['as' => 'manage.finance.deta_excel', 'uses' => 'FinanceController@deta_excel']);
    Route::get('finance/rank_excel', ['as' => 'manage.finance.rank_excel', 'uses' => 'FinanceController@rank_excel']);
    Route::controller('store', 'StoreController', ['postAddstore' => 'manage.store.add', 'getIndex' => 'manage.store.index', 'getEdit' => 'manage.store.edit', 'postStore' => 'manage.store.store', 'getShow' => 'manage.store.show', 'getCity' => 'manage.store.city']); //店铺
    //团购管理
    Route::Controller('group', 'GroupController', ['getIndex' => 'manage.group.index', 'getGoods' => 'manage.group.goods', 'getGroupinfo' => 'manage.group.groupinfo', 'getUploade' => 'manage.group.uploade', 'getLoad' => 'manage.group.load', 'getExamine' => 'manage.group.examine', 'getSwitch' => 'manage.group.switch', 'getLookinfo' => 'manage.group.lookinfo', 'postJs'=>'manage.group.js']);
    //运费模板
    Route::Controller('shipp', 'ShippController', ['getIndex' => 'manage.shipp.index', 'getProvince' => 'manage.shipp.city', 'postShipp' => 'manage.shipp.shipp', 'getEdit' => 'manage.shipp.edit']);
    //版本控制
    Route::get('version/ios', ['as' => 'manage.version.ios', 'uses' => 'VersionController@ios']);
    Route::get('version/android', ['as' => 'manage.version.android', 'uses' => 'VersionController@android']);
    //商品管理 第三方
    Route::get('goods/shop_category', ['as' => 'manage.goods.shop_category', 'uses' => 'GoodsController@shop_category']); //第三方店铺店内分类
    Route::get('goods/supplier_category_add', ['as' => 'manage.goods.supplier_category_add', 'uses' => 'GoodsController@supplier_category_add']);
    Route::get('goods/supplier_category_del/{id}', ['as' => 'manage.goods.supplier_category_add', 'uses' => 'GoodsController@supplier_category_del']);
    Route::post('goods/update_supplier_cat', ['as' => 'manage.goods.update_supplier_cat', 'uses' => 'GoodsController@update_supplier_cat']);
    Route::get('goods/update_supplier_price', ['as' => 'manage.goods.update_supplier_price', 'uses' => 'GoodsController@update_supplier_price']);
    Route::get('goods/recycle_bin', ['as' => 'manage.goods.recycle_bin', 'uses' => 'GoodsController@recycle_bin']); //第三方店铺店内分类
    Route::get('goods/supplier_category_transfer', ['as' => 'manage.goods.supplier_category_transfer', 'uses' => 'GoodsController@supplier_category_transfer']); //第三方店铺店内分类转移
    Route::get('goods/supplier_status', ['as' => 'manage.goods.supplier_status', 'uses' => 'GoodsController@supplier_status']); //物品审核
    Route::get('goods/third_party', ['as' => 'manage.goods.third_party', 'uses' => 'GoodsController@third_party']); //管理员审核
    Route::post('goods/updategoods', ['as' => 'manage.goods.name', 'uses' => 'GoodsController@updategoods']);
    Route::get('goods/update_price', ['as' => 'manage.goods.best', 'uses' => 'GoodsController@update_price']);
    Route::get('goods/addgoods', ['as' => 'manage.goods.addgoods', 'uses' => 'GoodsController@addgoods']);
    Route::get('goods/goods_update/{id}', ['as' => 'manage.goods.goods_update', 'uses' => 'GoodsController@goods_update']);
    Route::post('goods/catonly_add', ['as' => 'manage.goods.cat_onlyadd', 'uses' => 'GoodsController@catonly_add']);

});

/*
  |--------------------------------------------------------------------------
  | api Routes
  |--------------------------------------------------------------------------
  |
*/

//Route::post('index' ,'api\v1\CheckOutController@postIndex');
//Route::get('show' ,'api\v1\CartController@getShow');

Route::group(['namespace' => 'client'], function () {
    Route::post('router', 'RouterController@index')->middleware('api');
    Route::post('refund', 'RefundController@webhook')->middleware('api');
    Route::post('webhook', 'RouterController@webhook')->middleware('api');
    Route::post('uploads', 'RouterController@uploads');//上传图片入口
    Route::get('qrcode', 'RouterController@getQrCode');//
    Route::get('redirect', 'RouterController@wechat_redirect');//
    Route::get('openid', 'RouterController@getopneid');//
});


