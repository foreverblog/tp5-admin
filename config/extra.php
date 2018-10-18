<?php
/**
 * 自定义配置文件
 * author: ShenYan.
 * Email：52o@qq52o.cn
 * CreatedTime: 2018/10/13 20:59
 */
return [
    // 配置邮件发送服务器
    'smtp_debug'                    => false,
    'smtp_host'                     => 'smtp.exmail.qq.com',
    'smtp_username'                 => 'notice@foreverblog.cn',//SMTP服务器登陆用户名
    'smtp_password'                 => '',//SMTP服务器登陆密码
    'smtp_secure'                   => 'ssl', //tls 端口25 ssl465
    'smtp_port'                     => '465', //tls 端口25 ssl465
    // 网站域名
    'web_domain'                     => 'http://join.foreverblog.cn',
];
