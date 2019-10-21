<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Session;
use think\facade\Cookie;
use app\index\model\Blogs;
use think\Db;
class Blog extends Controller
{
   //登录页面
	public function bkReg(){
		return view("login");
	}
   //登录功能
	public function bkLogin(){
		$data=input("post.");
		$pwd=$data['b_password'];
       //MD5加密
		$user=$data['b_username'];
		$sel=new Blogs();
		$pwds= $sel->login($user);
        Session::set('data',$pwds);
        //判断用户是否存在
        if(empty($user)){
            echo "<script>alert('数据异常，重新登录');location.href='http://www.blog.com'</script>";
        }
        if(empty($pwds)){
            echo "<script>alert('你的用户名不存在,请重新登录');location.href='http://www.blog.com/'</script>";
        }
        //超级管理员
		if($pwds['pid']==0){
            $d_pwd=$pwds['b_password'];
            $b_pwd=md5($pwd);
            //登录成功
            $time=$pwds['b_time'];
            $str=strtotime($time);
            //超级管理员管理员一个月更换一次密码
            if(time()-$str>60*60*24*30){
                echo "<script>alert('请您尽快修改密码');location.href='/admin'</script>";
            }else{
                if($d_pwd==$b_pwd){
                    return $this->success("亲爱的".$user."用户您好，欢迎您本次登录","/admin");
                }
            }
            //密码错误提示
            if($d_pwd!=$b_pwd){
                echo "<script>alert('你的密码有误,请重新登录');location.href='/'</script>";
            }
        }
        //普通管理员
        if($pwds['pid']==1){
            $d_pwd=$pwds['b_password'];
            $b_pwd=md5($pwd);
            //登录成功
            $time=$pwds['b_time'];
            $str=strtotime($time);
            if(time()-$str>2505600){
                echo "<script>alert('请您尽快修改密码,还有一天就要到期');location.href='/'</script>";
            }else if(time()-$str>2592000){
                echo "<script>alert('您的账号已被封');location.href='/'</script>";
            }else{
                if($d_pwd==$b_pwd){
                    return $this->success("亲爱的".$user."用户您好，欢迎您本次登录","/admin");
                }
            }
            //密码错误提示
            if($d_pwd!=$b_pwd){
                echo "<script>alert('你的密码有误,请重新登录');location.href='/'</script>";
            }
        }
        //项目经理
        if($pwds['pid']==2){
            $d_pwd=$pwds['b_password'];
            $b_pwd=md5($pwd);
            $time=$pwds['b_time'];
            $str=strtotime($time);
                if($d_pwd==$b_pwd){
                    return $this->success("亲爱的".$user."您好，欢迎您本次登录","/staff");
                }
            //密码错误提示
            if($d_pwd!=$b_pwd){
                echo "<script>alert('你的密码有误,请重新登录');location.href='/'</script>";
            }
        }
        //组长
        if($pwds['pid']==3){
            $d_pwd=$pwds['b_password'];
            $b_pwd=md5($pwd);
            $time=$pwds['b_time'];
            $str=strtotime($time);
            if($d_pwd==$b_pwd){
                return $this->success("亲爱的".$user."您好，欢迎您本次登录","/staff");
            }

            //密码错误提示
            if($d_pwd!=$b_pwd){
                echo "<script>alert('你的密码有误,请重新登录');location.href='/'</script>";
            }
        }
        //员工
        if($pwds['pid']==4){
            $d_pwd=$pwds['b_password'];
            $b_pwd=md5($pwd);
            $time=$pwds['b_time'];
            $str=strtotime($time);
            if($d_pwd==$b_pwd){
                return $this->success("亲爱的".$user."您好，欢迎您本次登录","/staff");
            }

            //密码错误提示
            if($d_pwd!=$b_pwd){
                echo "<script>alert('你的密码有误,请重新登录');location.href='/'</script>";
            }
        }
	}
    //用户信息
    public function bUser(){
        $data=Session::get("data");
        $this->assign('data', $data);//用户名信息
    }
	//后台管理
	public function adMin()
	{
	    $data=Session::get("data");
        $this->assign('data', $data);//用户名信息
        $new=new Blogs();
        $res= $new->power();

        $this->assign('res', $res);
		return view("b_rbac");
	}
   //新增
    public function bAdd()
    {
        $data=Session::get("data");
        $this->assign('data', $data);
        $new=new Blogs();
        $res= $new->work();
        $this->assign('res', $res);

        return view("b_add");
    }
    public function bAddo()
    {
        $data=input("post.");
        //判断职业
        if($data['work']=="CEO"){
           //添加CEO
            if($data['section']=="技术部"){
                $data['b_time']=date("Y-m-d:H:i:s",time());
                $data['b_password']=md5($data['b_password']);
                $work=$data['work'];//职位
                $sec=$data['section'];//部门
                $new=new Blogs();
                $user=$data['b_username'];
                $red=$new->login($user);
                //唯一性
                if(empty($red)){
                    $bAdd=$new->bAdd($data);
                    if($bAdd){
                        $user=$data['b_username'];
                        $red=$new->login($user);
                        $pid=$red['pid'];
                        $id=$red['id'];
                        $upd=$new->bUpd($pid,$id);
                        return $this->success("添加成功","/admin");
                    }
                }else{
                    echo "<script>alert('你的用户名已存在,请重新登录');location.href='http://www.blog.com/badd'</script>";

                }
            }else{
                echo "<script>alert('您选错了部门，请重新选择');location.href='/badd'</script>";
            }
        }else if($data['work']=="项目经理"){
            if($data['section']=="技术部经理"){
                $data['b_time']=date("Y-m-d:H:i:s",time());
                $data['b_password']=md5($data['b_password']);
                $work=$data['work'];//职位
                $sec=$data['section'];//部门
                $new=new Blogs();
                $user=$data['b_username'];
                $red=$new->login($user);
                //唯一性
                if(empty($red)){
                    $bAdd=$new->bAdd($data);
                    if($bAdd){
                        $user=$data['b_username'];
                        $red=$new->login($user);
                        $selSta=$red['sel_sta'];
                        $pid=$red['pid'];
                        $id=$red['id'];
                        $upd=$new->bXm($pid,$selSta,$id);
                        return $this->success("添加成功","/admin");
                    }
                }else{
                    echo "<script>alert('你的用户名已存在,请重新登录');location.href='http://www.blog.com/badd'</script>";
                }
            }else{
                echo "<script>alert('您选错了部门，请重新选择');location.href='/badd'</script>";
            }
        }else if($data['work']=="组长"){
            if($data['section']=="技术部组长"){
                $data['b_time']=date("Y-m-d:H:i:s",time());
                $data['b_password']=md5($data['b_password']);
                $work=$data['work'];//职位
                $sec=$data['section'];//部门
                $new=new Blogs();
                $user=$data['b_username'];
                $red=$new->login($user);
                //唯一性
                if(empty($red)){
                    $bAdd=$new->bAdd($data);
                    if($bAdd){
                        $user=$data['b_username'];
                        $red=$new->login($user);
                        $selSta=$red['sel_sta'];
                        $pid=$red['pid'];
                        $id=$red['id'];
                        $upd=$new->bGroup($pid,$selSta,$id);
                        return $this->success("添加成功","/staff");
                    }
                }else{
                    echo "<script>alert('你的用户名已存在,请重新登录');location.href='http://www.blog.com/badd'</script>";

                }

            }else{
                echo "<script>alert('您选错了部门，请重新选择');location.href='/badd'</script>";
            }
        }else{
           if($data['work']=="员工"){
               if($data['section']=="技术部员工"){
                   $data['b_time']=date("Y-m-d:H:i:s",time());
                   $data['b_password']=md5($data['b_password']);
                   $work=$data['work'];//职位
                   $sec=$data['section'];//部门
                   $new=new Blogs();
                   $user=$data['b_username'];
                   $red=$new->login($user);
                   //唯一性

                   if(empty($red)){
                       $bAdd=$new->bAdd($data);
                       if($bAdd){
                           $user=$data['b_username'];
                           $red=$new->login($user);
                           $selSta=$red['sel_sta'];
                           $pid=$red['pid'];
                           $id=$red['id'];
                           $upd=$new->bWork($pid,$selSta,$id);
                           return $this->success("添加成功","/staff");
                       }
                   }else{
                       echo "<script>alert('你的用户名已存在,请重新登录');location.href='http://www.blog.com/badd'</script>";

                   }

               }else{
                   echo "<script>alert('您选错了部门，请重新选择');location.href='/badd'</script>";
               }
           }
        }
    }
    //员工列表
    public function bStaff(){
        $data=Session::get("data");
        $this->assign('data', $data);//用户名信息
        $new=new Blogs();
        $res=$new->bSel();
        //查询sel_sta为1的数据
        $this->assign("res",$res);
	    return view ("b_staff");
    }
    //删除管理员
    public function bDel(){
	   $id=input("get.id");
        if($id==1){
           echo "<script>alert('您不能删除');location.href='/admin'</script>";
        }else{
            $new=new Blogs();
            $res=$new->bDelete($id);
            if (empty($id)){
                echo "<script>alert('数据已删除');location.href='/admin'</script>";
            }
            if($res){
                echo "<script>alert('删除成功');location.href='/admin'</script>";
            }else{
                echo "<script>alert('删除失败');location.href='/admin'</script>";
            }
        }
    }
    //查询管理员的数据
    public function bjSel()
    {
        $id=input("get.id");
        $new=new Blogs();
        $res=$new->bjSel($id);
            $data=Session::get("data");
            $this->assign('data', $data);//用户名信息
            $this->assign("res",$res);
            return view('b_bjsel');
    }
    //修改管理员的数据
    public function bUpds(){
	    $data=input("post.");
	    $id=$data['id'];//id
        $b_password=md5($data['b_password']);//密码
      $time=  $data['b_time']=date("Y-m-d:H:i:s",time());//时间
        //boss修改
        if($id==1){
            //密码表的数据
            $new =new Blogs();
            $user=$data['b_username'];
            $reg=$new->upSel($user);
            //转成一维数组
            $red=array_column($reg,"upd_password");
            //判断密码是不是和前三次一样
            $ref= in_array($b_password,$red);
            if($ref){
                 echo "<script>alert('修改的密码不能和最近三次修改的密码一样');location.href='bjsel?id=$id'</script>";
            }else{
                $new =new Blogs();
                $res=$new->bUpds($b_password,$time,$id);
                if($res){
                    $user=$data['b_username'];//邮箱账号
                    $pwd= md5($data['b_password']);//密码
                    $data=['upd_user'=>$user,'upd_password'=>$pwd];
                    $new->updAdd($data);
                    echo "<script>alert('修改成功');location.href='/admin'</script>";
                }
            }
        }
        $new =new Blogs();
        $res=$new->bUpds($b_password,$time,$id);
        if($res){
            echo "<script>alert('修改成功');location.href='/admin'</script>";
        }
    }
    //用户名列表
    public function bUe()
    {
        $data=Session::get("data");
        $this->assign('data', $data);//用户名信息
        $new =new Blogs();
        $res=$new->bueSel();
        $this->assign('res',$res);
        return view("b_name");
    }
    //用户软删除
    public function b_rDel()
    {
        $id=$_GET['id'];
        $new =new Blogs();
        $rdel=$new->bStaf($id);
        if($rdel){
            echo "<script>alert('用户已被删除');location.href='/bue'</script>";
        }else{
            echo "<script>alert('用户未删除');location.href='/bue'</script>";
        }
    }
    //用户禁用
    public function bSeal()
    {
        $id=input("get.id");
        $b_Distime=date("Y-m-d:H:i:s",time()+60*60*24*3);//当前时间
        $new =new Blogs();
        $sealDel=$new->bsEal($id,$b_Distime);
        if($sealDel){
            echo json_encode(['code'=>200,'msg'=>'此号已禁言']);die;
        }else{
            echo json_encode(['code'=>2001,'msg'=>'此号未禁言']);die;
        }
    }
    //用户解禁
    public function bDesal()
    {
        $id=input("get.id");
        $new =new Blogs();
        $deSeal=$new->bdsEal($id);
        if($deSeal){
            echo json_encode(['code'=>200,'msg'=>'此号已经解除禁言']);die;
        }else{
            echo json_encode(['code'=>2001,'msg'=>'此号未解除禁言']);die;
        }
    }
    //用户封号
    public function bFnum()
    {
        $id=input("get.id");
        $new =new Blogs();
        $fNum=$new->bfNum($id);
        if($fNum){
            echo json_encode(['code'=>200,'msg'=>'此号已封']);die;
        }else{
            echo json_encode(['code'=>2001,'msg'=>'此号未封']);die;
        }
    }
    //广告添加页面
    public function badAdd()
    {
        $this->bUser();//用户信息
        return view ("b_ad");
    }
    //广告添加
    public function adAdo(){
	    $title=input("post.b_title");//广告标题
	    $link=input("post.b_adlink");//广告链接
        $img=$_FILES['b_img'];//广告图片
        $filename="E:\phpstudy\PHPTutorial\WWW\share/tp5.1/public/upload/".$img['name'];
        move_uploaded_file($img['tmp_name'],$filename);//移动图片
        $imgs="http://www.blog.com/upload/".$img['name'];
        $data=['b_title'=>$title,'b_adlink'=>$link,'b_img'=>$imgs];
        $new =new Blogs();
        $res=$new->bAd($data);
        if($res){
            return $this->success("添加广告成功","/badlist");
        }
    }
    //广告列表
    public function badList()
    {
        $this->bUser();//用户信息
        $new =new Blogs();
        $res=$new->adList();
        $this->assign("res",$res);
        return view("b_adlist");
    }
    //广告删除
    public function badDel()
    {
        $id=$_GET['id'];
        $new =new Blogs();
        $del=$new->badDel($id);
        if($del){
            echo "<script>alert('广告已经删除');location.href='/badlist'</script>";
        }else{
            echo "<script>alert('广告删除失败');location.href='/badlist'</script>";
        }
    }
    //友情链接添加页
    public function blAdd(){
        $this->bUser();//用户信息
	    return view("b_friendlink");
    }
    //友情链接添加
    public function blAdo()
    {
       $data=input("post.");
        $new =new Blogs();
       $res=$new->blAdd($data);
       if($res){
           return $this->success("友情链接已添加","/blist");
       }else{
           return $this->success("友情链接添加失败","/bladd");
       }
    }
    //友情链接列表页
    public function blList()
    {
        $this->bUser();//用户信息
        $new =new Blogs();
        $res=$new->bList();
        $this->assign('res',$res);
        return view("b_alist");
    }
    //友情链接删除
    public function blDel()
    {
        $id=input("get.id");
        $new =new Blogs();
        $bldel=$new->blDel($id);
        if($bldel){
            echo "<script>alert('友情链接已经删除');location.href='/blist'</script>";
        }else{
            echo "<script>alert('友情链接未删除');location.href='/blist'</script>";

        }
    }
    //敏感词管理页
    public function bSen()
    {
        $this->bUser();//用户信息
        return view("b_sensitive");
    }
    //添加敏感词
    public  function bsenAdd()
    {
        $data=input("post.");
        $new =new Blogs();
        $res=$new->bsEn($data);
        if($res){
            return $this->success("已添加敏感词","/bsen");
        }else{
            return $this->success("未添加敏感词","/bsen");
        }
    }
    //博文未审核列表页
    public function bAudit()
    {
        $this->bUser();//用户信息
        $new =new Blogs();
        $red=$new->bqUe();
        $this->assign("red",$red);
        return view("b_waduit");
    }
    //查看博文全文
    public function bQte()
    {
        $this->bUser();//用户信息
        $id=input("get.id");
        $new =new Blogs();
        $bQue=$new->bQw($id);
        $res=$new->bSb();
          $red=array_column($res,"b_errorinfo");
        //查询审核失败的原因
        $this->assign("red",$red);
        //查看全文信息
        $this->assign("bque",$bQue);
        return view("b_qutext");
    }
    //审核通过
    public function bPass()
    {
        $id=input("get.id");
        $new =new Blogs();
        //审核通过
        $res=$new->bpAss($id);
        if($res){
            echo json_encode(['code'=>4001,'msg'=>'审核通过失败']);die;
        }else{
            echo json_encode(['code'=>200,'msg'=>'审核通过']);die;
        }
    }
    //审核不通过
    public function bsPass()
    {
        $red=input("post.");
        $b_field=$red['b_field'];
        $id=$red['id'];
        $new =new Blogs();
        $res=$new->bSpass($id,$b_field);
        if($res){
            echo "<script>alert('审核不通过失败');location.href='/baduit'</script>";
        }else{
            echo "<script>alert('审核不通过成功');location.href='/baduit'</script>";
        }
    }
    //博文的审核通过列表
    public function bpasList()
    {
        $this->bUser();//用户信息
        $new =new Blogs();
        $red=$new->bpasList();
        $this->assign('red',$red);
        return view("b_paslist");
    }
    //博文的审核不通过列表
    public function bSplist()
    {
        $this->bUser();//用户信息
        $new =new Blogs();
        $red=$new->bsPlist();
//        var_dump($red);die;
        $this->assign('red',$red);
        return view("b_paslist");
    }
    //评论未审核列表
    public function bdisCus()
    {
        $new =new Blogs();
        $time=input("get.time");
        if(empty($time)){
            $this->bUser();//用户信息
            $new =new Blogs();
            $red=$new->bdiScu();
//            var_dump($red);die;
            $this->assign('red',$red);
            return view("b_conlist");
        }else{
            $this->bUser();//用户信息
            $new =new Blogs();
            $red=$new->bdiScus();
            echo  json_encode($red);
        }
    }
    //评论审核通过操作
    public function bContent(){
          $id=input("get.c_id");
          $new =new Blogs();
          $resCont=$new->bStg($id);
          if($resCont){
              echo "<script>alert('评论审核失败');location.href='/bdiscu'</script>";
          }else{
              echo "<script>alert('评论审核成功 ');location.href='/bdiscu'</script>";
          }
    }
    //评论审核不通过操作
    public function bTontent(){
        $id=input("get.c_id");
        $new =new Blogs();
        $resCont=$new->bBtg($id);
        if($resCont){
            echo "<script>alert('评论审核不通过失败');location.href='/bdiscu'</script>";
        }else{
            echo "<script>alert('评论审核不通过成功 ');location.href='/bdiscu'</script>";
        }
    }
   //评论审核通过列表
    public function bClist()
    {
        $this->bUser();//用户信息
        $new =new Blogs();
        $red=$new->bcList();
        $this->assign('red',$red);
        return view("b_contentlist");
    }
    //评论审核未通过列表
    public function bwList()
    {
        $this->bUser();//用户信息
        $new =new Blogs();
        $red=$new->bWlist();
        $this->assign('red',$red);
        return view("b_contentlist");
    }
}