<?php

namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Evaluation extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/Evaluation/getList 获取测评列表
     * @apiVersion 1.0.0
     * @apiName getList
     * @apiGroup Evaluate
     * @apiDescription 获取测评列表
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} type_name 测评名称.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     * type_id: "名称ID",
     * type_name: "名称标题",
     * equipment: "终端",
     * update_time: "更新时间"
     * }
     * ]
     * }
     */
    public function getList()
    {
        $type_name = input('param.type_name', '', 'htmlspecialchars');
        $page = input('param.page', '1', 'intval');
        $pagesize = input('param.pagesize', '10', 'intval');
        $evaluate_api = config('evaluate_api');
        $url = $evaluate_api . '/api/Evaluate/getList';
        $param['type_name'] = $type_name;
        $param['page'] = $page;
        $param['pagesize'] = $pagesize;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);

    }

    /**
     * @api {post} /data/Evaluation/editInfo 获取测评信息
     * @apiVersion 1.0.0
     * @apiName editInfo
     * @apiGroup Evaluate
     * @apiDescription 获取测评列表
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} type_id 测评ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    type_id: "名称ID",
     *    type_name: "名称标题",
     *    equipment: "终端（多个','分割）",
     *    cover: "封面（多个','分割）",
     *    header_img  "内容详情配图（多个','分割）",
     *    intro: 介绍,
     *    count: 虚拟使用数,
     *    sort: "排序",
     *    is_top: "置顶"
     *    update_time: "更新时间"
     *    img:[{
     *           "termType": "终端id",
     *           "cover": "封面图",
     *           "header_img": "详情头图"
     *      }]
     * }
     * ]
     * }
     */
    public function editInfo()
    {
        $type_id = input('param.type_id', '', 'intval');
        $evaluate_api = config('evaluate_api');
        $url = $evaluate_api . '/api/Evaluate/editInfo';
        $param['type_id'] = $type_id;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Evaluation/saveInfo 修改测评信息
     * @apiVersion 1.0.0
     * @apiName saveInfo
     * @apiGroup Evaluate
     * @apiDescription 修改测评信息
     *
     * @apiParam {String} time         请求的当前时间戳.
     * @apiParam {String} sign         签名.
     * @apiParam {int}    type_id      测评ID.
     * @apiParam {String} equipment    终端（多个','分割）
     * @apiParam {String} cover        封面（多个','分割）
     * @apiParam {String} header_img   内容详情配图（多个','分割）,
     * @apiParam {String} intro        介绍,
     * @apiParam {int}    sort         排序
     * @apiParam {int}    count        虚拟使用数,
     * @apiParam {int}    is_top       置顶
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveInfo()
    {
        $param = input('param.');
        if (strpos($param['equipment'], '3') === false) {
            $where['content_type'] = 2;
            $where['upload_id'] = $param['type_id'];
            $list = Db::name('teaching_content')->distinct(true)->where($where)->column('upload_id');
            if (count($list) > 0) {
                $this->response('-1', '新高中三端存在数据！');
            }
        }
        $evaluate_api = config('evaluate_api');
        $url = $evaluate_api . '/api/Evaluate/saveInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {post} /data/Evaluation/delInfo 删除测评信息
     * @apiVersion 1.0.0
     * @apiName delInfo
     * @apiGroup Evaluate
     * @apiDescription 删除测评信息
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} type_id 测评ID.
     * @apiParam {String} status: 状态 （1 正常 0 删除）.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function delInfo()
    {
        $type_id = input('param.type_id');
        $status = input('param.status', '0');
        $evaluate_api = config('evaluate_api');
        $url = $evaluate_api . '/api/Evaluate/delInfo';
        $param['type_id'] = $type_id;
        $param['status'] = $status;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/Evaluation/termTypeList 获取测评类型列表
     * @apiVersion 1.0.0
     * @apiName termTypeList
     * @apiGroup Evaluate
     * @apiDescription 获取测评类型列表
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
     *      id: "1",
     *      name: "APP"
     *  },
     *  {
     *      id: "2",
     *      name: "一体机"
     *  },
     *  {
     *      id: "3",
     *      name: "教案"
     *  },
     *  {
     *      id: "4",
     *      name: "学生web"
     *  }
     *  ]
     *  }
     */
    public function termTypeList()
    {
        $param = input('param.');
        $evaluate_api = config('evaluate_api');
        $url = $evaluate_api . '/api/Evaluate/termTypeList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }
}
