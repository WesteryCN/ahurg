<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Free_class extends Model
{
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
    protected $table = 'free_class';
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



    public static function listclass(){
            $data=['code'=>'0'];
            $classes = Classes::where('id','>','0')->get();
            if($classes){
                foreach ($classes as $temp_class){
                    $data['class'][$temp_class->id] = array([
                        'class_name' => $temp_class->class_name,

                    ]);
                }
                $data['code']='1';
            }
            else{
                $data['code']='0';
            }
            return $data;

    }




}
