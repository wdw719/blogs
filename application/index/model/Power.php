<?php

namespace app\index\model;

use think\Model;
use app\index\model\Role;
use app\index\model\User2;
use app\index\model\Rolepower;
class Power extends Model
{
    protected $table = 'Power';
    public function getpower($userid){
        $roleid=User2::where('id',$userid)->column('role_id');
//       return $roleid;
        $powerid=Rolepower::whereIn('roleid',$roleid)->column('powerid');
//        return $powerid;
        $powername=Power::whereIn('id',$powerid)->column('powername');
        return ['roleid'=>$roleid,'powerid'=>$powerid,'powername'=>$powername];



    }
}
