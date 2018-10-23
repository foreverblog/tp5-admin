<?php
/**
 * Created by PhpStorm.
 * User: ShenYan
 * Date: 2018/10/23
 * Time: 上午10:10
 */

namespace app\admin\controller;


class AdminUser extends Base
{
    public function index()
    {
        return $this->fetch();
    }
    
    public function userList()
    {
        $page = $this->request->param('page');
        $limit = $this->request->param('limit');
        $adminUser = app()->model('AdminUser');
        $data = $adminUser->getUserList($page, $limit);
        return $data;
    }
}
