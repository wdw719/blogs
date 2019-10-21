<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');
Route::any('blog', 'index/Blog/bkReg');//登录页面
Route::post('logins', 'index/Blog/bkLogin');//登录功能
Route::any('power', 'index/Blog/poWer');//权限控制
Route::any('powers', 'index/Blog/poWers');//权限控制分配
Route::any('admin', 'index/Blog/adMin');//后台管理
Route::any('adlist', 'index/Blog/adminList');//后台管理
Route::any('badd', 'index/Blog/bAdd');//添加
Route::any('baddo', 'index/Blog/bAddo');//添加管理员
Route::any('staff', 'index/Blog/bStaff');//员工列表
Route::get('bdel', 'index/Blog/bDel');//删除管理员
Route::get('bjsel', 'index/Blog/bjSel');//查询管理员数据
Route::post('bupds', 'index/Blog/bUpds');//修改管理员数据
Route::any('bue', 'index/Blog/bUe');//用户列表
Route::get('bseal', 'index/Blog/bSeal');//用户禁用
Route::get('bdseal', 'index/Blog/bDesal');//解禁用户账号
Route::get('bfnum', 'index/Blog/bFnum');//用户封号
Route::any('ad', 'index/Blog/badAdd');//广告添加页
Route::any('addo', 'index/Blog/adAdo');//广告添加
Route::any('badlist', 'index/Blog/badList');//广告列表
Route::any('badel', 'index/Blog/badDel');//删除广告
Route::any('rdel', 'index/Blog/b_rDel');//软删除用户
Route::any('bladd', 'index/Blog/blAdd');//友情链接添加页
Route::any('blado', 'index/Blog/blAdo');//友情链接添加
Route::any('blist', 'index/Blog/blList');//友情链接列表
Route::any('bldel', 'index/Blog/blDel');//删除友情链接
Route::any('bsen', 'index/Blog/bSen');//敏感词管理页
Route::post('bsadd', 'index/Blog/bsenAdd');//添加敏感词
Route::any('baduit', 'index/Blog/bAudit');//博文未审核的列表页
Route::get('bque', 'index/Blog/bQte');//博文的全文
Route::any('bpass', 'index/Blog/bPass');//审核通过
Route::any('bspass', 'index/Blog/bsPass');//审核不通过
Route::any('bpalist', 'index/Blog/bpasList');//审核通过列表
Route::any('bsplist', 'index/Blog/bSplist');//审核不通过列表
Route::any('bdiscu', 'index/Blog/bdisCus');//评论未审核列表
Route::any('bcon', 'index/Blog/bContent');//评论审核通过操作
Route::any('bton', 'index/Blog/bTontent');//评论审核不通过操作
Route::any('bclist', 'index/Blog/bClist');//评论审核通过列表
Route::any('bwlist', 'index/Blog/bwList');//评论审核未通过列表
Route::any('bdtime', 'index/Blog/bDtime');//评论时间降序




//前台
Route::any('reg', 'front/Share/bRegister');//用户注册页面
Route::any('regdo', 'front/Share/bRegdo');//用户注册
Route::any('login', 'front/Share/bLogin');//用户登录页面
Route::any('logdo', 'front/Share/bLogdo');//用户登录
Route::any('upcode', 'front/Share/bUpCode');//重新拉取验证码
Route::any('quit', 'front/Share/bQuit');//退出登录
Route::any('bpwd', 'front/Share/bWpwd');//忘记密码页面
Route::any('bzwd', 'front/Share/bZpwd');//忘记密码
Route::any('bxpwd', 'front/Share/bXpwd');//重置密码
Route::any('bfix', 'front/Share/bFixpwd');//修改密码
Route::any('bimg', 'front/Share/bImg');//添加头像
Route::any('bpic', 'front/Share/bPic');//头像
Route::any('write', 'front/Share/bRewrite');//写博文页面
Route::any('bwrite', 'front/Share/bWrite');//写博文
Route::any('show', 'front/Share/bShowblog');//博文正文
Route::any('content', 'front/Share/bComent');//博文评论
Route::any('home', 'front/Share/bHome');//首页
Route::any('bea', 'front/Share/bZan');//顶
Route::any('oppose', 'front/Share/oppOse');//踩
Route::any('coll', 'front/Share/bColl');//收藏
Route::any('batt', 'front/Share/bAttention');//关注
Route::any('myblog', 'front/Share/bMyblog');//我的博文
Route::any('mycoll', 'front/Share/bmyColl');//我的收藏
Route::any('mycol', 'front/Share/bmyCot');//我的评论
Route::any('many', 'front/Share/bMymany');//查看全文
Route::any('manys', 'front/Share/bMymanys');//查看全文
Route::any('delcon', 'front/Share/bDelcon');//删除评论
Route::any('delcoll', 'front/Share/bdelColl');//删除评论
Route::any('reco', 'front/Share/bReco');//我的推荐
Route::any('emails', 'front/Share/bEmails');//邮箱设置
Route::any('setemail', 'front/Share/setEmail');//修改信息
return [

];
