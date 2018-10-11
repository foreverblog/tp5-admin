<?php
/**
 * Created by PhpStorm.
 * User: ShenYan
 * Date: 2018/10/11
 * Time: 下午6:46
 */

namespace app\admin\model;


class AdminUser extends Base
{
    // 5.1中模型不会自动获取主键名称，必须设置pk属性
    protected $pk = 'id';

    // 自动完成
    protected $auto = ['now_ip', 'login_num', 'now_time'];

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

    public function login($data)
    {

    }
}