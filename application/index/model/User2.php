<?php

namespace app\index\model;
use think\facade\Session;
use think\Model;
use app\index\model\Power;

class User2 extends Model
{
    protected $table = 'User2';

    public function quanname(){
      $data=Session::get('data2');
      $powerid=$data['powerid'];
//        var_dump($powerid);die;
        $power=Power::whereIn('id',$powerid)->select();
//        var_dump($power);

        $arr= $this->digui($power);
//        var_dump($arr);die;
        return $arr;
    }

    public function digui($power,$pid=0){
        $arr=[];
        foreach ($power as $key => $value) {

            if($value['pid']==$pid){
                $value['child']=$this->digui($power,$value['id']);
                $arr[]=$value;
            }
        }
        return $arr;
    }
}
