<?php

namespace app\index\model;

use think\Model;
use think\Db;
class Blogs extends Model
{
    //登录
   public function login($user){
       return DB::table("b_user")->where('b_username',$user)->find();
   }
   //权限控制
    public function power(){
        return DB::table("b_user")->paginate(6);
    }
    //根据职业状态查询
    public function work(){
        return DB::table("b_work")->select();
    }
   //添加管理员
    public function bAdd($data)
    {
        return Db::name('b_user')->insert($data);
    }
    //修改CEO
    public function bUpd($pid,$id)
    {
       return  Db::name('b_user')->update(['pid' => $pid+1,'id'=>$id]);
    }
    //修改项目经理
    public function bXm($pid,$selSta,$id)
    {
        return  Db::name('b_user')->update(['pid' => $pid+2,'sel_sta'=>$selSta+1,'id'=>$id]);
    }
    //修改组长
    public function bGroup($pid,$selSta,$id)
    {
        return  Db::name('b_user')->update(['pid' => $pid+3,'sel_sta'=>$selSta+1,'id'=>$id]);
    }
    //员工
    public function bWork($pid,$selSta,$id)
    {
        return  Db::name('b_user')->update(['pid' => $pid+4,'sel_sta'=>$selSta+1,'id'=>$id]);
    }
    //查询pid不为0的数据
    public function bSel()
    {
        return Db::table("b_user")->where('pid','<>','0')->select();
    }
    //删除管理员
    public function bDelete($id){
       return Db::table("b_user")->where('id',$id)->delete();
    }
    //根据id查询数据
    public function bjSel($id){
        return Db::table("b_user")->where('id',$id)->find();
    }
    //修改管理员
    public function bUpds($b_password,$time,$id){
        return  Db::name('b_user')->update(['b_password'=>$b_password,'b_time'=>$time,'id'=>$id]);
    }
    //把修改的密码放入密码库
    public function updAdd($data)
    {
        return Db::name('upd_pwd')->insert($data);
    }
//    查询密码表前三条数据
   public function upSel($user)
   {
       return DB::table("upd_pwd")->where('upd_user',$user)->order('id desc')->limit(3)->select();
   }
    //查询用户数据
    public function bueSel(){
       return Db::table("b_name")->where('b_sel',0)->paginate(6);
    }
    //添加广告
    public function bAd($data)
    {
        return Db::name('b_ad')->insert($data);
    }
    //查询广告数据
    public function adList()
    {
        return Db::table("b_ad")->paginate(3);
    }
    //广告删除
    public function badDel($id){
        return Db::table("b_ad")->where('id',$id)->delete();
    }
    //用户软删除
    public function bStaf($id){
        return  Db::name('b_name')->update(['b_sel'=>1,'id'=>$id]);
    }
    //用户禁言号
    public function bsEal($id,$b_Distime)
    {
        return  Db::name('b_name')->update(['b_snum'=>1,'b_distime'=>$b_Distime,'id'=>$id]);
    }
    //用户解禁账号
    public function bdsEal($id)
    {
        return  Db::name('b_name')->update(['b_snum'=>0,'b_distime'=>'null','id'=>$id]);
    }
    //用户封号
    public function bfNum($id)
    {
        return  Db::name('b_name')->update(['b_snum'=>2,'b_sel'=>1,'id'=>$id]);
    }
    //友情链接添加
    public function blAdd($data)
    {
        return Db::name('b_blogroll')->insert($data);
    }
    //查询友情链接数据
    public function bList()
    {
        return Db::name('b_blogroll')->select();
    }
    //友情链接删除
    public function blDel($id)
    {
        return Db::name('b_blogroll')->delete(['id'=>$id]);
    }
    //添加敏感词
    public function bsEn($data)
    {
        return Db::name('b_sensitive')->insert($data);
    }

    //博文未审核列表
    public function bqUe()
    {
        return Db::table("b_text")->where('b_sta',0)->paginate(4);
    }
    //博文全文
    public function bQw($id)
    {
        return Db::table("b_text")->where('b_id',$id)->select();
    }
    //查询审核失败原因表
    public function bSb()
    {
       return Db::table("b_errinfo")->select();
    }
    //审核通过
    public function bpAss($id)
    {
         Db::table("b_text")->update(['b_sta'=>1,'b_id'=>$id]);
    }
    //审核失败
    public function bSpass($id,$b_field)
    {
        Db::table("b_text")->update(['b_sta'=>2,'b_field'=>$b_field,'b_id'=>$id]);
    }
    //查询审核通过数据
    public function bpasList()
    {
        return Db::table("b_text")->where('b_sta',1)->paginate(3);
    }
    //查询审核不通过数据
    public function bsPlist()
    {
        return Db::table("b_text")->where('b_sta',2)->paginate(3);
    }
    //评论未审核列表
    public function bdiScu()
    {
        return Db::table('b_name')
            ->alias('a')
            ->join(['b_content'=>'b'],'a.id=b.b_uid')
            ->where('b.b_stas',0)
            ->select();
    }
    //评论未审核列表时间降序
    public function bdiScus()
    {
        return Db::table('b_name')
            ->alias('a')
            ->join(['b_content'=>'b'],'a.id=b.b_uid')
            ->where('b.b_stas',0)
            ->order('b.b_ptime desc')
            ->select();
    }
    //评论审核通过操作
    public function bStg($id)
    {
        Db::table("b_content")->update(['b_stas'=>1,'c_id'=>$id]);
    }
    //评论审核通过操作
    public function bBtg($id)
    {
        Db::table("b_content")->update(['b_stas'=>2,'c_id'=>$id]);
    }
    //评论审核通过列表
    public function bcList()
    {
        return Db::table('b_name')
            ->alias('a')
            ->join(['b_content'=>'b'],'a.id=b.b_uid')
            ->where('b.b_stas',1)
            ->paginate(4);
    }
    //评论审核未通过列表
    public function bWlist()
    {
        return Db::table('b_name')
            ->alias('a')
            ->join(['b_content'=>'b'],'a.id=b.b_uid')
            ->where('b.b_stas',2)
            ->paginate(4);
    }
    public function bMgc()
    {
        return Db::table("b_sensitive")->select();
    }

}
