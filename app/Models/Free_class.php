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

    public static function isreal($class_id){
        $class = Classes::where('id','=',$class_id)->first();
        if($class){
            return 1;
        }
        return 0;
    }

    public static function listfree($class_id){
        $data=['code'=>'0'];
        if(self::isreal($class_id)){
            $data=['code'=>'1'];
            $class = self::where('c_id',$class_id)->get();
            //初始化教室空闲时间
            for ($ii=1;$ii<=9;$ii++){
                for ($i1=1;$i1<=7;$i1++){
                    for ($i2=1;$i2<=12;$i2++){
                        $data['time'][$ii][$i1][$i2]=0;

                    }

                }
            }

            if($class){
                foreach ($class as $temp_class){
                    $data['time'][$temp_class->week][$temp_class->day][$temp_class->time]=1;
                }
                //检测教室有占用情况
            }else{
                //教室无占用情况
            }
            return $data;

        }else{
            return $data;//教室id不存在
        }

    }

    public static function setfree($s_id,$c_id,$week,$day,$time){
        $data=['code'=>'0'];
        if(self::isreal($c_id)){

            $class = self::where('c_id',$c_id)->where('week',$week)->where('day',$day)
            ->where('time',$time)->first();
            if($class){
                $data=['code'=>'1'];
                //检测教室当前时间已占用
            }else{
                //教室无占用
                self::insert([
                    's_id' => $s_id,
                    'c_id' => $c_id,
                    'week' => $week,
                    'day' => $day,
                    'time' => $time,
                ]);

                $data=['code'=>'2'];

            }
            return $data;

        }else{
            return $data;//教室id不存在
        }

    }

    public static function getmyfree($s_id){
        $data=['code'=>'0'];
        $class = self::where('s_id',$s_id)->get();
        if($class){
            foreach ($class as $temp_class){
                $data['time'][$temp_class->week][$temp_class->day][$temp_class->time]=1;
            }
            $data['code']=1;
        }
        return $data;

    }
    public static function delmyfree($s_id,$c_id,$week,$day,$time){
        $data=['code'=>'0'];
        $class = self::where('s_id',$s_id)->where('c_id',$c_id)->where('week',$week)->where('day',$day)
            ->where('time',$time)->first();
        if($class){
            $class->delete();
            $data['code']=1;
        }

        return $data;

    }


}
