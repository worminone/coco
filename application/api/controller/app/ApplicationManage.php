<?php

namespace app\api\controller\app;

use think\Db;
use \app\message\model\Message;
use think\Request;
use app\common\controller\Api;

class ApplicationManage extends Api
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /Api/app.ApplicationManage/getAppList 获取应用类型列表
     * @apiVersion 1.0.0
     * @apiName getAppList
     * @apiGroup Application
     * @apiDescription 获取应用类型列表
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     *  code: "1",
     *  msg: "操作成功",
     *  data: [
     *  {
     *      id:   ,
     *      title: "库名称",
     *      cover_img: "图片",
     *      intro: "介绍"
     *  }
     *  ]
     *  }
     */
    public function getAppList()
    {
        $field = 'id,title,cover_img,intro,is_top,count,number';
        $list = $this->getPageList('Application', '','is_top desc,sort desc, id desc', $field,  '');
        foreach ($list['list'] as $key =>$value) {
            $list['list'][$key]['count'] = $value['number'] + $value['count'];
        }
        if ($list) {
            $this->response('1', '操作成功', $list);
        } else {
            $this->response('1', '暂无数据', $list);
        }
    }

    public function addViewNumber()
    {
        $id = input('id', '', 'intval');
        $res = DB::name('Application')->where(['id'=>$id])->setInc('number');
        return $res;
    }
}
