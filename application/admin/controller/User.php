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
            if (!empty($uid)) {
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

        if ($data == false) {
            return ['code' => 1, 'msg' => '账号或密码错误'];
        } elseif ($data == 1){
            return ['code' => 1, 'msg' => '账号待激活或已被禁止登入'];
        } else {
            return ['code' => 0, 'msg' => '登入成功', 'data' => ['access_token' => $this->setToken()]];
        }
    }

    /**
     * 后台管理员注册
     * @param Request $request
     * @return array|mixed
     */
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
        return $adminUser->registerByEmail($data);

    }

    public function activation($userId, $activeCode)
    {
        $adminUser = app()->model('AdminUser');
        $data = $adminUser->activation($userId, $activeCode);
        if ($data == false) {
            return $this->error('验证失败，激活失败', '/admin/user/register');
        }
        if ($data == 1) {
            return $this->error('已经激活成功，无需重复激活', '/admin/user/login');
        }
        return $this->success('验证成功，激活成功', '/admin');
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

}
