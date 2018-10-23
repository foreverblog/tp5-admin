<?php
/**
 * Created by PhpStorm.
 * User: ShenYan
 * Date: 2018/10/11
 * Time: 下午6:47
 */

namespace app\admin\model;

use think\Model;

class Base extends Model
{
    // 获取器 对模型实例的（原始）数据做出自动处理
    public function getCreateAtAttr($value)
    {
        $time = date('Y-m-d H:i:s', $value);
        return $time;
    }

    public function getLevelIdAttr($value)
    {
        $status = [1=>'超级管理员',2=>'管理员',3=>'审核员'];
        return $status[$value];
    }
}
