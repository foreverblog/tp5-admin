<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 发送邮件方法
 * @param string $to：接收者邮箱地址
 * @param string $title：邮件的标题
 * @param string $content：邮件内容
 * @return boolean  true:发送成功 false:发送失败
 */
function sendMail($to,$title,$content){
    //实例化PHPMailer核心类
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    // 获取配置
    $config = config('extra.');
    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug =  $config['smtp_debug'];
    //使用smtp鉴权方式发送邮件
    $mail->isSMTP();
    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;
    //邮件发送smtp服务器
    $mail->Host = $config['smtp_host'];
    //设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = $config['smtp_secure'];
    //设置ssl连接smtp服务器的远程服务器端口号
    $mail->Port = $config['smtp_port'];
    //设置smtp的helo消息头 这个可有可无 内容任意
    $mail->Helo = 'Hello www.foreverblog.cn Server';
    //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
    $mail->Hostname = 'foreverblog.cn';
    //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
    $mail->CharSet = 'UTF-8';
    //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = '十年之约项目组';
    //smtp登录的账号
    $mail->Username = $config['smtp_username'];
    //smtp登录的密码 使用生成的授权码 你的最新的授权码
    $mail->Password = $config['smtp_password'];
    //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->From = $config['smtp_username'];
    //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
    $mail->isHTML(true);
    //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
    $mail->addAddress($to,'十年之约');
    //添加多个收件人 则多次调用方法即可
    // $mail->addAddress('xxx@qq.com','lsgo在线通知');
    //添加该邮件的主题
    $mail->Subject = $title;
    //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    $mail->Body = $content;

    //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
    // $mail->addAttachment('./d.jpg','mm.jpg');
    //同样该方法可以多次调用 上传多个附件
    // $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');

    $status = $mail->send();

    //简单的判断与提示信息
    if($status) {
        return true;
    }else{
        return false;
    }
}
