<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Free_class;
use Illuminate\Http\Request;

    /**
     * 安徽大学软件工程概论实验
     * 管理员API模块
     * by: 刘方祥
     * i@2git.cn
     * i@westery.cn
     */

class AdminController extends Controller
{

    /**
     * 管理员用户登录
     */

    public function login(Request $request)
    {
        $data = [];
        try{
            $user = Admin::getUser($request->input('user'),$request->input('passwd'));
            if ($user) {
                //$data['user'] = $user;
                return apiResponse('0', '管理员登陆成功！', $user) ;
            }
            else {
                return apiResponse('301', '管理员登陆失败！', $user) ;
            }

        }catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }

    }

    /**
     * 用户登录，并置cookie，便于使用postman调试
     */

    public function logincookie(Request $request)
    {
        $response = $this->login($request);
        if($response->getData()->code == '0'){
            $data = $response->getData()->data;
            $response =$response ->cookie('token', $data->token, 3600);
            return $response;
        }else{
            return apiResponse('402', '管理员登陆失败(cookie)！') ;
        }
    }

    /**
     * 用户登出，将token失效
     */

    public function logout(Request $request)
    {
        $data = [];
        $data['user'] = $request->user;
        $data['name'] = $request->name;
        $data['token'] = $request->token;
        try {
            $msg = Admin::tokenInvalidate($request->token);

        }catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }
        $data['msg'] = $msg;
        return apiResponse('0', '管理员退出成功！', $data) ;

    }


    /**
     * 重设当前用户密码
     */
    public function setPasswd(Request $request)
    {
        $data = [];
        if(strlen( $request->input('passwd')) < 6 ){
            return apiResponse('401', '密码过短，请设置长于6字符的密码！', $data) ;
        }
        $data['user']=$request->user;
        try{
            $re_msg = Admin::setPasswd($request->user,$request->input('passwd'));
            $data['re_msg'] = $re_msg;
            return apiResponse('0', '管理员密码修改成功！', $data) ;

        }catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }


    }



    /**
     * 获取管理员信息
     */
    public function getAdminInfo(Request $request){
        $data = [];
        $t_id = $request -> input('t_id');
        if($t_id == null)
            $t_id = $request->id;
        try{
            $data = Admin::getAdminInfoById($t_id);
            if($data['code']=='1'){
                return apiResponse('0', '管理员信息获取成功！', $data) ;
            }else{
                return apiResponse('401', '管理员信息不存在！', $data) ;
            }

        }catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }


    }

    /**
     * 删除申请的空闲教室
     */

    public static function delfree(Request $request)
    {
        if($request->input('c_id') =="" or $request->input('week') ==""
            or $request->input('day') =="" or $request->input('time') ==""){
            return apiResponse('301', '教室id、申请时间不能为空。') ;
        }
        $data = [];
        try {
            $data = Free_class::delfree($request->input('c_id'),$request->input('week'),$request->input('day'),$request->input('time'));
            if ($data['code'] == '1') {
                return apiResponse('0', '删除教室空闲成功！', $data);
            } else {
                return apiResponse('401', '删除教室空闲失败！', $data);
            }
        } catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }
    }

    /**
     * 列出所有的申请
     */

    public static function getallfree(Request $request)
    {
        $data = [];
        try {
            $data = Free_class::getallfree();
            if ($data['code'] == '1') {
                return apiResponse('0', '教室空闲信息获取成功！', $data);
            } else {
                return apiResponse('401', '教室空闲信息获取失败！', $data);
            }
        } catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }
    }

    /**
     * 删除申请的空闲教室
     */

    public static function setstatus(Request $request)
    {
        if($request->input('c_id') =="" or $request->input('week') ==""
            or $request->input('day') =="" or $request->input('time') =="" or $request->input('status') ==""){
            return apiResponse('301', '教室id、申请时间、同意状态不能为空。') ;
        }
        $data = [];
        try {
            $data = Free_class::setstatus($request->input('c_id'),$request->input('week'),$request->input('day'),$request->input('time'),$request->input('status'));
            if ($data['code'] == '1') {
                return apiResponse('0', '修改申请状态成功！', $data);
            } else {
                return apiResponse('401', '修改申请状态失败！', $data);
            }
        } catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }
    }


}

?>
