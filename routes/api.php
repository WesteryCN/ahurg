<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** 安徽大学软件工程概论实验
  * 路由部分
  * by: 刘方祥
  * i@2git.cn
  * i@westery.cn
  */


Route::post('admin/login','AdminController@login');
Route::post('admin/loginc','AdminController@logincookie');

Route::post('student/login','StudentController@login');
Route::post('student/loginc','StudentController@logincookie');

//管理员管理模块
Route::middleware(['token.checkAndRenew.admin'])->prefix('admin')->group(function () {
    Route::post('info', 'AdminController@getAdminInfo'); //调取管理员信息
    Route::get('info', 'AdminController@getAdminInfo'); //调取管理员信息
    Route::get('logout', 'AdminController@logout'); //登出
    Route::post('setpasswd', 'AdminController@setpasswd'); //置密码

    Route::get('getallfree', 'AdminController@getallfree'); //列出所有教室
    Route::post('setstatus', 'AdminController@setstatus'); //修改申请空闲教室的状态

    Route::post('delfree', 'AdminController@delfree'); //删除申请空闲教室

});

//学生管理模块
Route::middleware(['token.checkAndRenew.student'])->prefix('student')->group(function () {
    Route::get('info', 'StudentController@getinfo'); //获取信息
    Route::get('logout', 'StudentController@logout'); //登出
    Route::post('setpasswd', 'StudentController@setpasswd'); //修改密码

    Route::get('listclasses', 'StudentController@listclasses'); //列出所有教室
    Route::get('getmyfree', 'StudentController@getmyfree'); //列出我申请的所有教室
    Route::post('listfree', 'StudentController@listfree'); //查询教室空闲时间
    Route::post('setfree', 'StudentController@setfree'); //申请空闲教室
    Route::post('delmyfree', 'StudentController@delmyfree'); //删除申请空闲教室


});

