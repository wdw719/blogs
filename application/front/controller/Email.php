<?php
namespace app\front\controller;

use think\Controller;
use PHPMailer\PHPMailer\PHPMailer;
class Email extends Controller
{
    function sendMail($to,$title,$content){

        //实例化PHPMailer核心类
        $mail = new PHPMailer();
        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $mail->SMTPDebug = 1;
        //使用smtp鉴权方式发送邮件
        $mail->isSMTP();
        //smtp需要鉴权 这个必须是true
        $mail->SMTPAuth=true;
        //链接qq域名邮箱的服务器地址
        $mail->Host = 'smtp.qq.com';
        //设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';//设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
        $mail->Port = 465;
        //设置smtp的helo消息头 这个可有可无 内容任意
//     $mail->Helo = 'Hello smtp.qq.com Server';
        //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
        $mail->Hostname = 'localhost';
        //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
        $mail->CharSet = 'UTF-8';
        //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = '乐享博客';
        //smtp登录的账号 这里填入字符串格式的qq号即可
        $mail->Username ='1948639942@qq.com';
        //smtp登录的密码 使用生成的授权码 你的最新的授权码
        $mail->Password = 'cklnffhayueidage';
        //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
        $mail->From = '1948639942@qq.com';

        $mail->addAddress($to,'测试通知');
        $mail->Subject = $title;
        $mail->Body = $content;


        $status = $mail->send();

        if($status)
        {
            return true;
        }else{
            return false;
        }
    }
}
