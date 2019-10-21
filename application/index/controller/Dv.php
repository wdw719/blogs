<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Session;
use think\facade\Cookie;
use app\index\model\Blogs;
use think\Db;
class Dv extends Controller
{
    public function Ds()
    {
        $id=1;
       $da= Db::table('b_name')->fetchSql()
            ->alias('a')
            ->join(['b_blogshort'=>'b'],'a.id=b.b_shortid')
            ->join(['b_text'=>'c'],'b.b_shortid=c.b_textid')
           ->where("a.id",$id)
            ->select();
    }
}