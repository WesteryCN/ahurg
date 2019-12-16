<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use App\Models\Free_class;
use Illuminate\Http\Request;

    /**
     * 安徽大学软件工程概论实验
     * 学生API模块
     * by: 刘方祥
     * i@2git.cn
     * i@westery.cn
     */
class StudentController extends Controller
{

    /**
     * 学生用户登录
     */

    public function login(Request $request)
    {
        $data = [];
        try{
            $user = Student::getUser($request->input('user'),$request->input('passwd'));
            if ($user) {
                //$data['user'] = $user;
                return apiResponse('0', '学生登陆成功！', $user) ;
            }
            else {
                return apiResponse('301', '学生登陆失败！', $user) ;
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
            return apiResponse('302', '学生登陆失败(cookie)！') ;
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
            $msg = Student::tokenInvalidate($request->token);

        }catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }
        $data['msg'] = $msg;
        return apiResponse('0', '学生退出成功！', $data) ;

    }

    /**
     * 获取当前学生信息
     */

    public function getinfo(Request $request)
    {
        $data = [];
        try{
            $data = Student::getinfo($request->user);
            return apiResponse('0', '学生信息获取成功！', $data) ;
        }catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }
    }

    /**
     * 重设当前用户密码
     */

    public function setPasswd(Request $request)
    {
        $data = [];
        if(strlen( $request->input('passwd')) < 6 ){
            return apiResponse('301', '密码过短，请设置长于6字符的密码！', $data) ;
        }
        $data['user'] = $request->user;
        try{
            $re_msg = Student::setPasswd($request->user,$request->input('passwd'));
            $data['re_msg']=$re_msg;
            return apiResponse('0', '学生密码修改成功！', $data) ;

        }catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }


    }



    public static function listclasses(Request $request){
        $data = [];
        try{
            $data = Free_class::listclass();
            if($data['code'] == '1'){
                return apiResponse('0', '班级信息获取成功！', $data) ;
            }else{
                return apiResponse('401', '班级不存在！', $data) ;
            }
        }catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }


    }

    public static function listfree(Request $request){
        $data = [];
        try{
            $data = Free_class::listfree($request->input('c_id'));
            if($data['code'] == '1'){
                return apiResponse('0', '教室空闲信息获取成功！', $data) ;
            }else{
                return apiResponse('401', '教室不存在！', $data) ;
            }
        }catch (\Exception $e) {
            return $e;
            //return $this->internalErrRes();
        }


    }

}







?>
