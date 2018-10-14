<?php
/**
 * Created by PhpStorm.
 * User: ShenYan
 * Date: 2018/10/11
 * Time: 下午6:15
 */
namespace app\admin\controller;

use think\Controller;
use think\Request;

class User extends Controller
{
    // 后台管理员登陆
    public function login(Request $request)
    {
        if (request()->isGet()) {
            // 判断用户是否登录
            $uid = session('user_id');
            if (isset($uid)) {
                return $this->error('您已经登录，请进行操作即可', '/admin');
            }
            return $this->fetch();
        }
        $userName = $request->param('username');
        $password = $request->param('password');
        $verCode = $request->param('vercode');

        if(!captcha_check($verCode)){
            // 验证失败
            return ['code' => 1, 'msg' => '验证码错误'];
        }

        $data = [
            'userName' => $userName,
            'password' => md5(md5($password))
        ];
        $adminUser = app()->model('AdminUser');
        $data = $adminUser->login($data);
        if ($data != false) {
            return ['code' => 0, 'msg' => '登入成功', 'data' => ['access_token' => $this->setToken()]];
        }
        return ['code' => 1, 'msg' => '账号或密码错误'];
    }

    // 后台管理员注册
    public function register(Request $request)
    {
        if (request()->isGet()) {
            return $this->fetch();
        }
        $userName = $request->param('username');
        $password = $request->param('password');
        $verCode = $request->param('vercode');
        $email = $request->param('email');

        if(!captcha_check($verCode)){
            // 验证失败
            return ['code' => 1, 'msg' => '验证码错误'];
        }

        $data = [
            'userName' => $userName,
            'password' => md5(md5($password)),
            'email' => $email
        ];
        $adminUser = app()->model('AdminUser');
        $data = $adminUser->registerByEmail($data);

    }

    // 后台管理员退出
    public function logout()
    {
        session('user_id', null);
    }

    protected function setToken()
    {
        return call_user_func('sha1', $this->server('REQUEST_TIME_FLOAT'));
    }

    protected function sendRegisterEmail($to,$title,$content)
    {
        $data = sendMail($to, $title, $content);
        if ($data != false) {
            return  '发送成功';
        }
    }
}