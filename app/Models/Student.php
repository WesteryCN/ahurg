<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


    /**
     * 安徽大学软件工程概论实验
     * 学生数据库部分
     * by: 刘方祥
     * i@2git.cn
     * i@westery.cn
     */
class Student extends Model{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    /**
     * @var bool 主键是否自增
     */
    public $incrementing = true;
    /**
     * @var bool 数据表包含created_at和updated_at字段
     */
    public $timestamps = false;
    /**
     * @var string 模型对应的数据表
     */
    protected $table = 'student';
    /**
     * @var string 主键名
     */
    protected $primaryKey = 'id';
    /**
     * @var string 主键类型
     */
    protected $keyType = 'int';
    /**
     * @var array 为空，则所有字段可集体赋值
     */
    protected $guarded = [];
    /**
     * @var array 序列化时隐藏的字段
     */
    protected $hidden = ['token_expired_at',];

    /**
     *登录模块，成功则置新的token
     */

    public static function getUser($userName, $psw)
    {
        $user = Student::where('s_number', $userName)->where('password',md5($psw) )->first();
        if ($user) {
            $token = substr(md5(strval(uniqid()). 'ahulfx' ), 0, 16);
            $user -> update([
                'token' => $token,
                'token_expired_at' => date('Y-m-d H:i:s', time() + 36000)
            ]);
            $data=[];
            $data['id'] = $user ->id;
            $data['user'] = $userName;
            $data['token'] = $token;
            return $data;
        } else {
            return [];
        }
    }

    /**
     *通过token取出用户名
     */

    public static function getUserByToken($token)
    {
        $data=[];
        $time = date('Y-m-d H:i:s', time());
        $user = Student::where('token', $token)->where('token_expired_at', '>', $time)->first();
        if ($user) {
            $data['id'] = $user['id'];
            $data['user'] = $user['s_number'];
            $data['name'] = $user['name'];
            $data['token'] = $token;

            return $data;
        } else {
            return [];
        }



    }

    /**
     *更新token的有效时间
     */

    public static function renewToken($token)
    {
        Student::where('token', $token)->first()
            ->update([
                'token_expired_at' => date('Y-m-d H:i:s', time() + 36000)
            ]);
    }

    /**
     *使token失效
     */

    public static function tokenInvalidate($token)
    {
        $time = date('Y-m-d H:i:s', time());
        $user = Student::where('token', $token)->first();
        if ($user)
            $user->update([
                'token_expired_at' => $time
            ]);
    }

    /**
     *重置密码模块
     */

    public static function setPasswd($userName, $passwd)
    {
        $time = date('Y-m-d H:i:s', time());
        Student::where('s_number', $userName)->firstOrFail()
            ->update([
                'password' => md5($passwd),
                'token_expired_at' => $time
            ]);
    }

    /**
     * @param $userName
     * @return array
     * 获取学生个人信息
     */

    public static function getinfo($userName){
        $user = Student::where('s_number', $userName)->first();
        $data=[];
        if($user){
            $data['s_number'] = $user->s_number;
            $data['class_id'] = $user->class_id;
            $data['name'] = $user->name;
            $data['sex'] = $user->sex;
            $data['grade'] = $user->grade;
            $data['academy'] = $user->academy;
            $data['email'] = $user->email;
            return $data;
        }
        return $data;

    }


    public static function getnamebyid($s_id){
        $user = Student::where('id', $s_id)->first();
        if($user){
            return $user->name;
        }
        return 0;

    }



}
