<?php
//
///*
//|--------------------------------------------------------------------------
//| Web Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register web routes for your application. These
//| routes are loaded by the RouteServiceProvider within a group which
//| contains the "web" middleware group. Now create something great!
//|
//*/
//
Route::get('/', function () {
    return view('welcome');
});
//微信成为开发者
Route::get('admin/developer',"Admin\AdminController@developer");

//登录视图
Route::get('admin/admin_login',"Admin\AdminController@admin_login");
//执行登录
Route::post('admin/admin_login_do',"Admin\AdminController@admin_login_do");
//登录成功
Route::get('admin/lists',"Admin\AdminController@lists")->middleware('session_midd')->middleware('session_midd');
//检查是否有用户扫描二维码（检查cache的缓存中是否有值）
Route::get("admin/exam","Admin\AdminController@examOpen");


Route::get('a',function(){
    return view('a');
});





//Route::get("test","TestController@test");
//Route::get("login","TestController@login");
//Route::get("test/login_do","TestController@login_do");
//
//
//
////前台路由
//Route::domain('index.zhangkang.com')->namespace("Index")->group(function () {
//    //展示登录视图
//    Route::get('/index/login', function ( ) {
//        return view("login.login");
//    });
//    //执行登录
//    Route::get('/index/login_do',"IndexController@login_do");
//
//});
//
//Route::domain('admin.zhangkang.com')->namespace("Admin")->group(function () {
// Route::get("admin","AdminController@admin");
//
//});
//Route::domain('api.zhangkang.com')->namespace("Api")->group(function () {
//    Route::any("login_do","ApiController@login_do");
//
//});
//Route::get("tests/test","Index\IndexController@test");
//
////周考中间件
////use App\Http\Middleware\Appid;
//* //周考
//    /*
//Route::domain('www.appid.com')->namespace("Appid")->group(function () {
//    //登录视图
//    Route::any('logins',function(){
//        return view("appid.login");
//    });
//    //执行登录
//    Route::any('logins_do',"AppidController@logins_do");
//    //登录后跳转到的页面
//    Route::any('user','AppidController@user');
//    //点击申请
//    Route::any('shenqing',"AppidController@shenqing")->middleware(Appid::class);
//    //点击调用接口
//    Route::any('diaoyong','AppidController@diaoyong');
//});
//    */
//中间件
//Route::domain('www.appid.com')->namespace("Appid")->group(function (){
//    Route::any("a","AppidController@a");
//    Route::any("b","AppidController@b");
//
//});

