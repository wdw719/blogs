<?php
namespace app\front\controller;

use think\Controller;
use think\facade\Session;
use think\facade\Cookie;
use app\front\model\User;
use think\Db;
class Share extends Controller
{
   public function initialize()
    {
        session_start();
    }
    //用户注册页面
    public function bRegister()
    {
         return view("b_register");
    }
    //邮箱
    public function bEmail(){
        include "Email.php";
    }
    //用户注册
    public function bRegdo()
    {
        $bData=input("post.");//接受的数据
        $bse=new User();
        $pwd=$bData['b_password'];
        Session::set("pwd",$pwd);
         $logname=$bData['b_logname'];
         $b_showname=$bData['b_showname'];
        $bsel=$bse->bUsel($logname);
        Session::set('user',$bsel);
        $bShow=$bse->bShow($b_showname);
        if(empty($bsel)){
              if(empty($bShow)){
                  $bData['b_password']=md5($bData['b_password']);//密码加密
                  $bNew=new User();
                  $res =$bNew->bReg($bData);
                  $code=md5(time().rand(1,9999));//用户注册的code码
                  $time=date("Y-m-d:H:i:s",time());//用户注册的时间
                  $uid=$res;//用户id
                  $dtime=date("Y-m-d:H:i:s",time()+60*60*2);//用户登录的有效期
                  if($res){
                      $red=$bNew->bCode($code,$time,$uid,$dtime);     //添加用户code码
                      echo "<script>alert('注册成功');location.href='/login'</script>";;
                  }else{
                      echo "<script>alert('注册成功');location.href='/reg'</script>";
                  }
              }else{
                  echo "<script>alert('您输入的显示名称已存在');location.href='/reg'</script>";
              }
        }else{
            echo "<script>alert('您输入的登录名称已存在');location.href='/reg'</script>";
        }
    }
    //用户登录页面
    public function bLogin(){
      return view("b_login");
    }
    public function bLogdo()
    {
        $data=input("post.");
        $bNew=new User();
        $logname=$data['b_logname'];
        $res=$bNew->bFel($logname);
        Session::set("bname",$res);//用户信息
        $userid=$res['id'];

       if($res['b_snum']==2){
           echo "<script>alert('您的账号已经被封号了');location.href='/login'</script>";
       }
        if(empty($res)){
            echo "<script>alert('您输入的登录名称不存在');location.href='/login'</script>";
        }
        $id=$res['id'];
        $red=$bNew->bSj($id);   //u_code信息
        Session::set("ucode",$red);//用户操作信息
        $dtime=strtotime($red['b_dtime']);  //code过期时间
        if($red['b_sta']==1){
            if(md5($data['b_password'])!=$res['b_password']){
                echo "<script>alert('密码错误');location.href='/login'</script>";
            }
            echo "<script>alert('登录成功');location.href='/home'</script>";
        }
        if(time()<$dtime){
            if($red['b_sta']==0){
                echo "<script>alert('您的账号还未激活点击激活');location.href='/login'</script>";
            $url="https://mail.qq.com/cgi-bin/frame_html";
            $email=$this->bEmail();
            $code=$red['code'];
            $emails=new Email();
          $ids=base64_encode($id);
            $url="http://www.blog.com/home?code=$code&id=$ids";
            $em=$res['b_email'];
            $content="亲爱的".$res['b_showname']."用户您好，请点击激活您的账号".$url;//发送的内容
            $emails->sendMail($em,"乐享博客激活账号",$content);
            }
        }else{
            //重新拉取验证码
            echo "<script>alert('您的验证码已经过期,请重新获取验证码');location.href='/login'</script>";
            if($red['b_sta']==0){
                $url="https://mail.qq.com/cgi-bin/frame_html";
                $email=$this->bEmail();
                $code=md5(time().rand(1,9999));
                $emails=new Email();
                $ids=base64_encode($id);
                $time=base64_encode(time()+60*60*3);
                $url="http://www.blog.com/upcode?code=$code&id=$ids&times=$time";
                $em=$res['b_email'];
                $content="亲爱的".$res['b_showname']."用户您好，请重新获取验证码".$url;//发送的内容
               $d=$emails->sendMail($em,"乐享博客激活账号",$content);
            }
        }

    }
    //首页页面
    public function bHome(){
        $bNew=new User();

        $data=input("get.");//验证码

        if(empty($data)){
        }else{
            $id=base64_decode($data['id']);  //激活账号
            $bNew=new User();
            $bStasel=$bNew->bselSta($id);
            $uid=$bStasel['uid'];

            if($bStasel['code']==$data['code']){
                $red=$bNew->bActcode($uid);  //激活账号
            }
            $bSl=$bNew->bselSta($id);
            if($bSl['b_sta']==1){
                $userInfo= Session::get("bname");//用户信息
                $this->assign('user',$userInfo);
                echo "<script>alert('您的账号已经激活');location.href='/home'</script>";
            }
            Session::set("code",$bStasel['code']);
        }
        if(empty($_SESSION['think']['bname'])){
            $res=$bNew->bBloglist();  //未登录状态
            $this->assign('data',$res);
            return view("b_home");
        }else{
            $red =Session::get("bname");
            $id=$red['id'];
//            登录状态
            $sel=$bNew->bImgs($id);
            $this->assign('sel',$sel);
            $up=Session::get('up');//图片
            $this->assign('up',$up);
            $name=$_SESSION['think']['bname'];
            $nameid=$name['id'];      //查询用户的信息
            $attnum=$bNew->numAtt($nameid);
            $count=count($attnum);   //关注数
            $pink=$bNew->numPink($nameid);
            $counts=count($pink);    //粉丝数
            $names=$bNew->userInfo($id);
            Session::set("names",$names);
            $time=$_SESSION['think']['ucode'];
            $tiems=$time['b_times'];
            $str=strtotime($tiems);
              $s=(time()-$str)/86400;
            $d=floor($s);
            $data=$bNew->bBloglist();
            $this->assign('data',$data);
            return view("b_home",['name'=>$name,'time'=>$d,'data'=>$data,'count'=>$count,'counts'=>$counts]);
        }

    }
    //获取新的code
    public function bUpCode()
    {
        $data=input("get.");
        $id=base64_decode($data['id']);
        $code=$data['code'];
        $time=base64_decode($data['times']);
        $times=date("Y-m-d:H:i:s",$time);
        $bNew=new User();
        $bStasel=$bNew->bselSta($id);   //查询code信息
        $uid=$bStasel['uid'];
        $newCode=$bNew->bNewcode($uid,$times,$code);
        if($newCode){
            echo "<script>alert('重新获取验证码成功，点击登录');location.href='/login'</script>";
        }
    }
    //退出登录
    public function bQuit()
    {
        $d=$_SESSION["think"]=array();
        if(empty($d)){
           return $this->redirect("/login");
        }
    }
    //忘记密码页
    public function bWpwd()
    {
         return view("b_pwd");
    }
    //找回密码
    public function bZpwd()
    {
        $logName=input("post.b_logname");
        $bNew=new User();
        $zPwd=$bNew->bZpwd($logName);//查询用户的信息
        $this->assign("users",$zPwd);
        $id=$zPwd['id'];
        $zPsta=$bNew->bselSta($id);    //code
        $this->assign("code",$zPsta);
        return view("b_zpwd");
    }
    //重置密码
    public function bXpwd()
    {
        $bId=input("post.id");
        $bPwd=input("post.b_password");
        $bCode=input("post.code");
        $bNew=new User();
        $email=$this->bEmail();
        echo "<script>alert('提交成功');location.href='/login'</script>";
        $emails=new Email();
        $ids=base64_encode($bId);
        $bPwd=md5($bPwd);
        $url="http://www.blog.com/bfix?code=$bCode&id=$ids&pwd=$bPwd";
       $res= Session::get("bname");//用户信息
        $em=$res['b_email'];
        $content="亲爱的".$res['b_showname']."用户您好，请点击修改您的密码 ".$url;//发送的内容
        $emails->sendMail($em,"乐享博客",$content);
    }
    //修改密码
    public function bFixpwd()
    {
        $data=input("get.");
        if(empty($data)){
            echo "<script>alert('数据异常'),location.href='/login'</script>";
        }else{
            $id=base64_decode($data['id']);
            $code=$data['code'];
            $pwd=$data['pwd'];
            $bNew=new User();
            $zPsta=$bNew->bselSta($id);    //code
            if($code==$zPsta['code']){
                $reg=$bNew->bXpwd($id,$pwd);
                if($reg){
                    echo "<script>alert('成功修改密码,点击去登录'),location.href='/login'</script>";
                }
            }
        }
    }
    //添加头像
    public function bImg()
    {
        $id=$_GET['id'];
        $this->assign('id',$id);
        return view("b_img");
    }
    public function bPic()
    {
        $id=$_POST['id'];
        $file=$_FILES['file'];
        $filename="E:\phpstudy\PHPTutorial\WWW\share/tp5.1/public/upload/".$file['name'];//图片
        $imgs="http://www.blog.com/upload/".$file['name'];
        move_uploaded_file($file['tmp_name'],$filename);
        $bNew=new User();
        $bImg=$bNew->biMg($imgs,$id);
        if($bImg){
            echo "<script>alert('上传成功');location.href='/'</script>";
        }else{
            echo "<script>alert('上传失败');location.href='/'</script>";
        }
        $up=$bNew->bImgs($id);

         Session::set('up',$up);
    }
    //写博文页面
    public function bRewrite()
    {
        $user= Session::get('bname');
        if(empty($user)){
            echo "<script>alert('您还没有登录');location.href='/'</script>";
        }
       return view("b_rewrite");
    }
    //写博文
    public function bWrite()
    {
       $user= Session::get('bname');
       if(empty($user)){
           echo "<script>alert('您还没有登录');location.href='/'</script>";
       }
        $name= Session::get("names");
        $data=input("post.");
        $data['b_text']=$data['b_text'][0];
        $data['b_createtime']=date("Y-m-d:H:i:s",time());//当前时间
        $data['userid']=$user['id'];//作者
        $data['b_author']=$name['b_showname'];
        $bNew=new User();
        $bWblog=$bNew->bWblog($data);
        if($bWblog){
           echo json_encode(['code'=>200,'msg'=>'发布成功']);die;
        }else{
            echo json_encode(['code'=>4001,'msg'=>'发布失败']);
        }
    }
    //博文正文
    public function bShowblog()
    {
        if(empty($_GET['id'])){
            echo "<script>alert('数据异常');location.href='/home'</script>";
            echo json_encode(['code'=>404,'msg'=>'请先登录 ']);die;
        }
        if(empty($_SESSION['think']['bname'])){
            if(empty($_SESSION['think']['ucode'])){
                $id=$_GET['id'];        //查询的id
                $bNew=new User();
                $blogCon=$bNew->bBlogcon($id);  //博客正文
                $blist=$bNew->bD($id);//三条
                $data=$bNew->bCd($id);//全部
                $count=count($data);
                $up =Session::get('up');
                return view("b_blogcontent",['id'=>$id,'up'=>$up,'blog'=>$blogCon,'count'=>$count,'blist'=>$blist,'data'=>$data]);
            }
        }else{
                $id=$_GET['id'];   //查询的id
                $bNew=new User();
                $blogCon=$bNew->bBlogcon($id); //博客正文
                $pv=$blogCon['b_pv'];                  //浏览
                $b_pv=$bNew->bPv($id,$pv);
                $blogs=$bNew->bBlogcon($id);  //博客正文
                Session::set("high",$blogs);
                $name=$_SESSION['think']['bname'];
                $names=Session::get("names");
            $nameid=$names['id'];
              $attnum=$bNew->numAtt($nameid);
              $count=count($attnum);   //关注数
              $pink=$bNew->numPink($nameid);
              $counts=count($pink);    //粉丝数
                $ids=$blogs['id'];
                $blist=$bNew->bD($id);//三条评论
                $data=$bNew->bCd($id);//全部评论
                $bid= $blogs['b_id'];//文章id
                $userid=$names['id'];
                $zent=$bNew->bZnwen($userid);//根据用户查询是否推荐关联表的文章信息
                $zen=array_column($zent,"wen_id");
                $zens=in_array($bid,$zen);
                $vs=$bNew->bZa($id);//查询是否点赞的状态
                $cols= $bNew->bCols($userid);//根据用户查询是否收藏联表的文章信息
                $col=array_column($cols,"bid");//查询收藏关联表的文章id
                $coll=in_array($bid,$col);
                $att=$bNew->bAtts($userid);  //根据用户查询是否关注博主
                $atten=array_column($att,"dataid");
//                var_dump($atten);die;
                $atts=in_array($ids,$atten);
                $countss=count($data);
                $up =Session::get('up');
                $time=$_SESSION['think']['ucode'];
                $tiems=$time['b_times'];
                $str=strtotime($tiems);
                $s=(time()-$str)/86400;
                $d=floor($s);
                return view("b_blogcontent",['id'=>$id,'name'=>$names,'time'=>$d,'up'=>$up,'blog'=>$blogs,'countss'=>$countss,'blist'=>$blist,'data'=>$data,'zen'=>$zens,'vs'=>$vs,'coll'=>$coll,'att'=>$atts,'count'=>$count,'counts'=>$counts]);
            }

//        }
    }
    //评论
    public function bComent()
    {
        if(empty($_POST['id'])){
            echo json_encode(['code'=>404,'msg'=>'请先登录 ']);die;
        }
       $ref= Session::get("bname");//用户信息
        Db::startTrans();//开启事务
          $data=input("post.");
         $id=$data['id'];//用户的id
         $ids=$data['ids'];//文章的b_id
        $coment=$data['con'];//评论的内容
         $con=$data['b_con'];//评论的数量
         $time=date("Y-m-d:H:i:s",time());
         $name=$ref['b_showname'];
         $img=$ref['b_pic'];
        $bNew=new User();
        $red=['b_uid'=>$id,'b_content'=>$coment,'b_ptime'=>$time,'b_artid'=>$ids,'b_names'=>$name,'b_img'=>$img];//当前时间
        $coment=$bNew->bComent($red); //添加评论的内容
        $text=$bNew->bText($ids,$con);
        if($coment==false||$text==false){
            Db::rollback();
            echo json_encode(['code'=>4001,'msg'=>'发表失败']);die;
        }else{
            Db::commit();
            echo json_encode(['code'=>200,'msg'=>'发表成功']);die;
        }
    }
    //顶
    public function bZan()
    {
         if(empty($_POST)){
             echo json_encode(['code'=>404,'msg'=>'数据异常']);die;
         }
        $bNew=new User();
         $data=input("post.");
        $b_id=$data['id'];//文章的id
        $id=$data['ids'];//用户的id
           $zan_nums=$bNew->bZn($b_id);   //查询赞的数量
            $zan_num=$zan_nums['b_zannum'];
       $red=['user_id'=>$id,'wen_id'=>$b_id,'zan_sta'=>1]; //添加到关联表
          $bAdd= $bNew->bZanart($red);
        if($bAdd){
            $bNew->bUpzan($b_id,$zan_num);
            echo json_encode(['code'=>200,'msg'=>'支持成功']);die;
        }else{
            echo json_encode(['code'=>40001,'msg'=>'支持失败']);die;
        }
    }
    //反对||踩
    public function oppOse()
    {
        if(empty($_POST)){
            echo json_encode(['code'=>404,'msg'=>'数据异常']);die;
        }
        $bNew=new User();
        $data=input("post.");
        $b_id=$data['id'];//文章的id
        $id=$data['ids'];//用户的id
        $fanums=$bNew->bCn($b_id);
        $b_fannums=$fanums['b_fannums'];//查询踩的数量
        $red=['user_id'=>$id,'wen_id'=>$b_id,'zan_sta'=>2]; //添加到关联表
         $bLow= $bNew->bZanart($red);
        if($bLow){
             $bNew->bUpcai($b_fannums,$b_id);
            echo json_encode(['code'=>200,'msg'=>'反对成功']);die;
        }else{
            echo json_encode(['code'=>40001,'msg'=>'反对失败']);die;
        }
    }
    //收藏
    public function bColl()
    {
        if(empty($_POST)){
            echo json_encode(['code'=>404,'msg'=>'数据异常']);die;
        }
        $bNew=new User();
        $id=input("post.id"); //用户的id
        $b_id=input("b_id");//文章的id
        $res=$bNew->bColnums($b_id);//查询文章的收藏数
        $coll_sta=$res['b_colnum'];
        $red=['userid'=>$id,'bid'=>$b_id,'coll_sta'=>1];//添加的数据
        $bColl=$bNew->bCol($red);
        if($bColl){
            $bNew->bColnum($b_id,$coll_sta);
            echo json_encode(['code'=>200,'msg'=>'收藏成功']);die;
        }else{
            echo json_encode(['code'=>40001,'msg'=>'收藏失败']);die;
        }
    }
    //关注博主
    public function bAttention()
    {
        if(empty($_POST)){
            echo json_encode(['code'=>404,'msg'=>'数据异常']);die;
        }
        $bNew=new User();
        $id=input("post.id"); //登录用户的id
        $res= $bNew->bAttnum($id);  //查询关注数
        $b_att=$res['b_att'];
        $userid=input("post.userid");//发表文章的用户id
        $time=date("Y-m-d:H:i:s",time());
        $red=['userid'=>$id,'dataid'=>$userid,'g_time'=>$time];
        $bAtt=$bNew->bAtt($red);
        if($bAtt){
            $bNew->bUpatt($b_att,$id);
            echo json_encode(['code'=>200,'msg'=>'关注成功']);die;
        }else{
            echo json_encode(['code'=>40001,'msg'=>'关注失败']);die;
        }
    }
    //我的博文
    public function bMyblog()
    {
        $name=Session::get("names");
        $id=$name['id'];
        $bNew=new User();
        $time=$_SESSION['think']['ucode'];
        $tiems=$time['b_times'];
        $nameid=$name['id'];      //查询用户的信息
        $attnum=$bNew->numAtt($nameid);
        $count=count($attnum);   //关注数
        $pink=$bNew->numPink($nameid);
        $counts=count($pink);    //粉丝数
        $str=strtotime($tiems);
        $s=(time()-$str)/86400;
        $d=floor($s);
         $myblog=$bNew->bMyblog($id);

       return view("b_myblog",['blog'=>$myblog,'name'=>$name,'time'=>$d,'count'=>$count,'counts'=>$counts]);
    }
    public function bMymany(){
        $name=Session::get("names");
        $id=input("get.id");
        $bNew=new User();
        $data=$bNew->bMany($id);
        return view('b_manytext',['data'=>$data,'name'=>$name]);
    }
    //我的收藏
    public function bmyColl()
    {
        $bNew=new User();
        $name=Session::get("names");
        $nameid=$name['id'];      //查询用户的信息
        $attnum=$bNew->numAtt($nameid);
        $count=count($attnum);   //关注数
        $pink=$bNew->numPink($nameid);
        $counts=count($pink);    //粉丝数
        $id=$name['id'];
        $coll=$bNew->bmyColl($id);
        $time=$_SESSION['think']['ucode'];
        $tiems=$time['b_times'];
        $str=strtotime($tiems);
        $s=(time()-$str)/86400;
        $d=floor($s);
        return view("b_mycoll",['name'=>$name,'coll'=>$coll,'time'=>$d,'count'=>$count,'counts'=>$counts]);
    }
    //我的评论
    public function bmyCot()
    {
        $bNew=new User();
        $name=Session::get("names");
        $nameid=$name['id'];      //查询用户的信息
        $attnum=$bNew->numAtt($nameid);
        $count=count($attnum);   //关注数
        $pink=$bNew->numPink($nameid);
        $counts=count($pink);    //粉丝数
        $id=$name['id'];
        $data=$bNew->bMycots($id);
        $time=$_SESSION['think']['ucode'];
        $tiems=$time['b_times'];
        $str=strtotime($tiems);
        $s=(time()-$str)/86400;
        $d=floor($s);
       return view("b_mycot",['data'=>$data,'name'=>$name,'time'=>$d,'count'=>$count,'counts'=>$counts]);
    }
    //删除评论
    public function bDelcon()
    {
        $id=input("post.id");//内容id
        $b_id=input("post.b_id");//文章id
        $bNew=new User();
       $b_cons= $bNew->bSeltext($b_id);//查询文章表
        $b_con=$b_cons['b_con'];
        $del=$bNew->bDelpl($id);
        if($del){
            $bNew->bClear($b_id,$b_con);
            echo json_encode(['code'=>200,'msg'=>'关注成功']);die;
        }else{
            echo json_encode(['code'=>40001,'msg'=>'关注失败']);die;
        }
    }
    //收藏 查看全文
    public function bMymanys(){
        $name=Session::get("names");
        $id=input("get.id");
        $bNew=new User();
        $data=$bNew->bMany($id);
        return view('b_colltext',['data'=>$data]);
    }
   //删除收藏
    public function bdelColl()
    {
        Db::startTrans();//开启事务
        $bNew=new User();
           $id=input("get.id");//收藏的id
           $b_id=input("get.b_id");//文章的id
           $res=$bNew->bSeltext($b_id);
           $b_colnum=$res['b_colnum'];
           $delcoll=$bNew->bdelColl($id);
           $bup=$bNew->bUpcoll($b_id,$b_colnum);
           if($delcoll==false||$bup==false){
               Db::commit();
               echo "<script>alert('删除成功');location.href='/mycoll'</script>";die;
           }else{
               Db::rollback();
               echo "<script>alert('删除失败');location.href='/mycoll'</script>";die;
           }
    }
    //我的推荐
    public function bReco()
    {
        $bNew=new User();
        $name=Session::get("names");
        $nameid=$name['id'];      //查询用户的信息
        $attnum=$bNew->numAtt($nameid);
        $count=count($attnum);   //关注数
        $pink=$bNew->numPink($nameid);
        $counts=count($pink);    //粉丝数
        $id=$name['id'];
        $data=$bNew->bReco($id);
        $time=$_SESSION['think']['ucode'];
        $tiems=$time['b_times'];
        $str=strtotime($tiems);
        $s=(time()-$str)/86400;
        $d=floor($s);
        return view("b_reco",['name'=>$name,'data'=>$data,'time'=>$d,'count'=>$count,'counts'=>$counts]);
    }
    //信息设置
    public function bEmails()
    {
        $bNew=new User();
        $name=Session::get("names");
        $nameid=$name['id'];      //查询用户的信息
        $attnum=$bNew->numAtt($nameid);
        $count=count($attnum);   //关注数
        $pink=$bNew->numPink($nameid);
        $counts=count($pink);    //粉丝数
        $id=$name['id'];
        $data=$bNew->bUserinfo($id);
        $time=$_SESSION['think']['ucode'];
        $tiems=$time['b_times'];
        $str=strtotime($tiems);
        $s=(time()-$str)/86400;
        $d=floor($s);
          return view("b_setemail",['name'=>$name,'data'=>$data,'time'=>$d,'count'=>$count,'counts'=>$counts]);
    }
    //修改信息
    public function setEmail()
    {
      $data=input("post.");
      $email=$data['b_email'];
      $logname=$data['b_logname'];
      $showname=$data['b_showname'];
        $id=$data['id'];
        $pwd=md5($data['b_password']);
        $bNew=new User();
        $up=$bNew->setEmail($email);
        $log=$bNew->setEmails($logname);
        if(empty($up)){
             if(empty($log)){
                  $ups= $bNew->upEmail($email,$logname,$showname,$id,$pwd);
                  if($ups){
                      echo "<script>alert('修改成功');location.href='/emails';location.href='/emails'</script>";die;
                  }else{
                      echo "<script>alert('修改失败');location.href='/emails';location.href='/emails'</script>";die;
                  }
             }else{
                 echo "<script>alert('您输入的用户名已经存在');location.href='/emails'</script>";die;
             }
        }else{
            echo "<script>alert('您输入的邮箱已经存在');location.href='/emails'</script>";die;
        }
    }
    //我的粉丝
    public function bBean()
    {
        $bNew=new User();
        $name=Session::get("names");
        $nameid=$name['id'];      //查询用户的信息
        $attnum=$bNew->numAtt($nameid);
        $count=count($attnum);   //关注数
        $pink=$bNew->numPink($nameid);
        $counts=count($pink);    //粉丝数
        $id=$name['id'];
        $data=$bNew->bUserinfo($id);
        $time=$_SESSION['think']['ucode'];
        $tiems=$time['b_times'];
        $str=strtotime($tiems);
        $s=(time()-$str)/86400;
        $d=floor($s);//博龄
      $bean= $bNew->bbEan($id);    //我的粉丝
        return view("b_bean",['name'=>$name,'time'=>$d,'count'=>$count,'counts'=>$counts,'bean'=>$bean]);
    }
}