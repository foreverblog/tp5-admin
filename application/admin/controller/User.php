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
    public function login(Request $request)
    {
        if (request()->isGet()) {
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

    protected function setToken(){
        return  $this->request->token('__token__', 'sha1');
    }
}