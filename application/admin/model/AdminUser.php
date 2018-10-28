<?php
/**
 * Created by PhpStorm.
 * User: ShenYan
 * Date: 2018/10/11
 * Time: 下午6:46
 */
namespace app\admin\model;

use mcrypt\Mcrypt;

class AdminUser extends Base
{
    // 5.1中模型不会自动获取主键名称，必须设置pk属性
    protected $pk = 'id';

    // 自动完成
    protected $update = ['now_ip', 'login_num', 'now_time'];
    protected $insert = ['login_num' => 0,'status' => 1, 'level_id' => 3, 'nick_name' => '请修改昵称'];

    // 登陆验证
    public function login($data)
    {
        $user = AdminUser::where('user_name', $data['userName'])->find();
        if (empty($user)) {
            return false;
        } elseif ($user['password'] != $data['password']) {
            return false;
        } elseif ($user['status'] != 0) {
            return 1;
        }
        $user->last_ip = $user['now_ip'];
        $user->last_time = $user['now_time'];
        $user->save();
        session('user', json_decode($user, true));
        session('user_id', $user['id']);
        return $user;
    }
    
    // 邮箱注册
    public function registerByEmail($data)
    {
        //检查用户名是否可用
        $user = AdminUser::where('user_name', $data['userName'])->find();
        if ($user) {
            return ['code' => 1, 'msg' => '用户名重复，请重新输入'];
        }
        //检查邮箱是否重复
        $user = AdminUser::where('user_email', $data['email'])->find();
        if ($user) {
            return ['code' => 1, 'msg' => '邮箱重复，请联系管理员'];
        }
        $user = new AdminUser;
        $user->user_name = $data['userName'];
        $user->user_email = $data['email'];
        $user->password = $data['password'];
        $user->create_at = time();
        $user->save();
        $activeCode = Mcrypt::encode($data['userName']);//生成激活码
        $email = $data['email'];
        //发送邮件
        $link = config('extra.web_domain') . url('activation') . '?user_id=' . $user->id . '&active_code=' . $activeCode;
        sendMail($email, '十年之约用户激活邮件', "欢迎注册十年之约。<br/>请点击下面的链接进行激活：<a target='_blank' href=" . $link . ">" . $link . "</a>");
        return ['code' => 0, 'msg' => '激活邮件已发送，请查看邮箱', 'data' => $user];
    }

    // 激活验证
    public function activation($userId, $activeCode)
    {
        //检查用户是否存在
        $user = AdminUser::get($userId);
        if (empty($user)) {
            return false;
        }
        if ($user->status = 1) {
            return 1;
        }
        $userName = $user->user_name;
        $userNameCode = Mcrypt::decode($activeCode);
        if ($userName != $userNameCode) {
            return false;
        }
        $user->status = 0;
        $status = $user->save();
        if (!empty($status)) {
            return true;
        }
    }

    public function getUserList($page, $limit)
    {
        $count = AdminUser::count();
        $user = AdminUser::page($page,$limit)->select();
        if (empty($count) && empty($user)) {
            return ['code' => 1, 'msg' => '暂无数据'];
        }
        return ['code' => 0, 'count' => $count, 'data' => $user, 'msg' => '查询成功'];
    }


    protected function setLoginNumAttr($value)
    {
        return $value + 1;
    }
    protected function setNowIpAttr()
    {
        return request()->ip();
    }
    protected function setNowTimeAttr()
    {
        return time();
    }
}
