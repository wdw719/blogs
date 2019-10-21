<?php

namespace app\front\model;

use app\index\model\Blogs;
use think\Model;
use think\Db;
class User extends Model
{
    //用户注册
    public function bReg($bData)
    {
        return Db::table("b_name")->insertGetId($bData);
    }
    //添加code
    public function bCode($code,$time,$uid,$dtime)
    {
        return Db::table("u_code")->insert(['code'=>$code,'b_times'=>$time,'uids'=>$uid,'b_dtime'=>$dtime]);
    }
  //logname登录名称
   public function bUsel($logname)
   {
       return Db::table("b_name")->where('b_logname',$logname)->select();
   }
   //b_showname显示名称
    public function bShow($b_showname)
    {
        return Db::table("b_name")->where('b_showname',$b_showname)->select();
    }
    //查询用户是否被封号
    public function bFel($logname)
    {
        return Db::table("b_name")->where('b_logname',$logname)->find();
    }
    //查看用户是否激活账号
    public function bSj($id)
    {
        return Db::table("u_code")->where('uids',$id)->find();
    }
    //查询状态
    public function bselSta($id){
        return Db::table("u_code")->where('uids',$id)->find();
    }
    //激活账号
    public function bActcode($uid)
    {
        return Db::table("u_code")->update(['b_sta'=>1,'b_sta'=>1,'uid'=>$uid]);
    }
    //更新code码
    public function bNewcode($uid,$times,$code)
    {
        return Db::table("u_code")->update(['code'=>$code,'b_sta'=>1,'b_dtime'=>$times,'uid'=>$uid]);
    }
    //找回密码
    public function bZpwd($logName)
    {
        return Db::table("b_name")->where('b_logname',$logName)->find();
    }
    //修改密码
    public function bXpwd($id,$pwd)
    {
       return Db::table("b_name")->update(['b_password'=>$pwd,'id'=>$id]);
    }
    public function biMg($imgs,$id)
    {
        return Db::table("b_name")->update(['b_pic'=>$imgs,'id'=>$id]);
    }
    //添加博文
    public function bWblog($data)
    {
       return Db::table("b_text")->insert($data);
    }
    //查询用户
    public function bImgs($id){
        return Db::table("b_name")->where('id',$id)->find();
    }
    //博客列表
    public function bBloglist()
    {
        return Db::table('b_text')
            ->alias('a')
            ->join(['b_name'=>'b'],'a.userid=b.id')
            ->where('a.b_public',1)
            ->where('a.b_sta',1)
            ->order('a.b_createtime desc')
            ->select();
    }
    //博客正文
    public function bBlogcon($id)
    {
        return Db::table('b_text')
            ->alias('a')
            ->join(['b_name'=>'b'],'a.userid=b.id')
            ->where('a.b_id',$id)
            ->find();
    }
    //添加评论内容
    public function bComent($red)
    {
        return Db::table("b_content")->insert($red);
    }
    //修改评论数量
    public function bText($ids,$con)
    {
        return Db::table("b_text")->update(['b_con'=>$con+1,'b_id'=>$ids]);
    }
    //根据时间查询评论内容的最近条数据
    public function bD($id)
    {
        return Db::table('b_content')
            ->where('b_artid',$id)
            ->where('b_stas',1)
            ->order('b_ptime desc')
            ->limit(3)
            ->select();

    }
    //查询全部评论数据
    public function bCd($id)
    {
        return Db::table('b_content')
            ->where('b_artid',$id)
            ->where('b_stas',1)
            ->order('b_ptime desc')
            ->select();
    }
    //查询用户
    public function userInfo($id)
    {
        return Db::table("b_name")->where('id',$id)->find();
    }
    //浏览
    public function  bPv($id,$pv)
    {
        Db::table("b_text")->update(['b_pv'=>$pv+1,'b_id'=>$id]);
    }
    //查询用户信息
    public function bUser($uid)
    {
        return Db::table("b_name")->where('id',$uid)->select();
    }
    //文章用户关联
    public function bZanart($red)
    {
        return Db::table("b_zan")->insert($red);
    }
    //根据用户查询是否推荐关联表的文章信息
    public function bZnwen($userid)
    {
        return Db::table("b_zan")->where('user_id',$userid)->select();
    }
    public function bZen($id){
        return Db::table("b_zan")->where('wen_id',$id)->select();
    }
    //查询赞的数量
    public function bZn($b_id)
    {
        return Db::table("b_text")->where('b_id',$b_id)->find();
    }

    //修改赞的数量
    public function bUpzan($b_id,$zan_num)
    {
        return Db::table("b_text")->update(['b_zannum'=>$zan_num+1,'b_id'=>$b_id]);
    }

    //查询踩的数量
    public function bCn($b_id)
    {
        return Db::table("b_text")->where('b_id',$b_id)->find();
    }
    //修改踩的数量
    public function bUpcai($b_id,$b_fannums)
    {
        return Db::table("b_text")->update(['b_fannums'=>$b_fannums+1,'b_id'=>$b_id]);
    }
    //查询是否点赞的状态
    public function bZa($id)
    {
       return Db::table("b_zan")->where("wen_id",$id)->find();
    }
    //添加到收藏关联表
    public function bCol($red)
    {
        return Db::table("b_collect")->insert($red);
    }
    //修改收藏的数量
    public function bColnum($b_id,$coll_sta)
    {
        return Db::table("b_text")->update(['b_colnum'=>$coll_sta+1,'b_id'=>$b_id]);
    }
    //查询收藏的数量
    public function bColnums($b_id)
    {
        return Db::table("b_text")->where('b_id',$b_id)->find();
    }
    //根据用户查询是否收藏联表的文章信息
    public function bCols($ids)
    {
        return Db::table("b_collect")->where('userid',$ids)->select();
    }
    //关注关联表
    public function bAtt($red)
    {
        return Db::table("b_attention")->insert($red);
    }
    //查询关注数
    public function bAttnum($id)
    {
        return Db::table("b_name")->where('id',$id)->find();
    }
    //更新关注数
    public function bUpatt($b_att,$id)
    {
        return Db::table("b_name")->update(['b_att'=>$b_att+1,'id'=>$id]);
    }
    //根据用户查询是否关注博主
    public function bAtts($userid){
        return Db::table("b_attention")->where('userid',$userid)->select();
    }
    //我的博文
    public function bMyblog($id)
    {
        return Db::table("b_text")->where('userid',$id)->paginate(5);
    }
    //查看全文
    public function bMany($id)
    {
        return Db::table("b_text")->where('b_id',$id)->find();
    }
    //我的评论
    public function bMycots($id)
    {
        return Db::table('b_name')
            ->alias('a')
            ->join(['b_content'=>'b'],'a.id=b.b_uid')
             ->join(['b_text'=>'c'],'b.b_artid=c.b_id')
            ->where('a.id',$id)
            ->paginate(5);
    }
    //删除评论
    public function bDelpl($id)
    {
        return Db::table("b_content")->delete($id);
    }
    //清空评论数
    public function bClear($b_id,$b_con)
    {
        return Db::table("b_text")->update(['b_con'=>$b_con-1,'b_id'=>$b_id]);
    }
    //查询评论数
    public function bSeltext($b_id)
    {
        return Db::table("b_text")->where('b_id',$b_id)->find();
    }
    //我的收藏
    public function bmyColl($id)
    {
        return Db::table('b_collect')
            ->alias('a')
            ->join(['b_text'=>'b'],'a.bid=b.b_id')
            ->where('a.userid',$id)
            ->where('a.coll_sta',1)
            ->paginate(5);
    }
    //删除收藏
    public function bdelColl($id){
          return Db::table("b_collect")->delete($id);
    }
    //修改收藏的数量
    public function bUpcoll($b_id,$b_colnum)
    {
        return Db::table("b_text")->update(['b_colnum'=>$b_colnum-1,'b_id'=>$b_id]);
    }
    //我的推荐
    public function bReco($id)
    {
        return Db::table('b_zan')
            ->alias('a')
            ->join(['b_text'=>'b'],'a.wen_id=b.b_id')
            ->where('a.zan_sta',1)
            ->where('a.user_id',$id)
            ->select();
    }
    //查看用户信息
    public function bUserinfo($id)
    {
        return Db::table("b_name")->where('id',$id)->find();
    }
    public function setEmail($email)
    {
        return Db::table("b_name")->where('b_email',$email)->find();
    }
    public function setEmails($blogname)
    {
        return Db::table("b_name")->where('b_logname',$blogname)->find();
    }
//    修改信息
     public function upEmail($email,$logname,$showname,$id,$pwd){
        return Db::table("b_name")->update(['b_email'=>$email,'b_logname'=>$logname,'b_showname'=>$showname,'id'=>$id,'b_password'=>$pwd]);
     }
     //关注数
     public function numAtt($nameid)
     {
         return Db::table("b_attention")->where('userid',$nameid)->column("dataid");
     }
     //粉丝数
    public function numPink($nameid)
    {
        return Db::table("b_attention")->where('dataid',$nameid)->column("userid");
    }
}