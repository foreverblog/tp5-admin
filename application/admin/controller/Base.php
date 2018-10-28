<?php
/**
 * Created by PhpStorm.
 * User: ShenYan
 * Date: 2018/10/11
 * Time: 下午1:46
 */
namespace app\admin\controller;

use think\Controller;

class Base extends Controller
{
    protected function initialize()
    {
        // 判断用户是否登录
        $uid = session('user_id');
        if (empty($uid)) {
            return $this->error('对不起，您还没有登录！请先登录', '/admin/user/login');
        }
    }
}
