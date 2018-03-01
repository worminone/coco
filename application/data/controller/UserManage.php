<?php

namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class UserManage extends Admin
{
    /**
     * @api {get|post} /data/UserManage/updateStatus 批量冻结恢复用户列表
     * @apiVersion 1.0.0
     * @apiName updateStatus
     * @apiGroup UserManage
     * @apiDescription 批量冻结恢复用户列表
     *
     * @apiDescription 批量冻结或恢复用户
     *
     * @apiParam {String} user_id 用户ID 多个用","分隔 （不为空时以下的条件无效）
     *
     * @apiSuccess {Int} code 错误代码，1是成功,-1失败
     *
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function updateStatus()
    {
        $param = input('param.');
        $base_api = config('base_api');
        $url = $base_api . '/api/user/updateStatus';
        curl_api($url, $param, 'post');
    }
}
