<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Application extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /data/Application/getList 获取应用列表
     * @apiVersion 1.0.0
     * @apiName getList
     * @apiGroup Application
     * @apiDescription 获取应用列表
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} title 测评名称.
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
     * title: "名称标题",
     * term_type: "终端",
     * update_time: "更新时间"
     * }
     * ]
     * }
     */
    public function getList()
    {
        $title = input('param.title', '', 'htmlspecialchars');
        $pagesize =input('param.pagesize', '10', 'int');
        $where = [];
        if (!empty($title)) {
            $where['title'] = array('like', '%,' . $title . ',%');
        }
        $list = $this->getPageList('Application', $where, '', '', $pagesize);
        foreach ($list['list'] as $key => $value) {
            $term_type = explode(',', $value['term_type']);
            $term_types = '';
            foreach ($term_type as $k => $v) {
                $term_types .= $this->termType()[$v].',';
            }

            $term_types = substr($term_types, 0, -1);
            $list['list'][$key]['term_type'] = $term_types;
            $list['list'][$key]['update_time'] = date('Y-m-d H:i', $value['update_time']);
        }

        if ($list) {
            $this->response('1', '操作成功', $list);
        } else {
            $this->response('1', '暂无数据', $list);
        }
    }

    /**
     * @api {post} /data/Application/editInfo 获取应用信息
     * @apiVersion 1.0.0
     * @apiName editInfo
     * @apiGroup Application
     * @apiDescription 获取应用信息
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int}    id   应用ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    id:        应用ID,
     *    title:     名称标题,
     *    term_type: 终端（多个','分割）,
     *    cover_img: 封面（多个','分割）,
     *    intro:     介绍,
     *    count      虚拟使用数,
     *    sort:      排序,
     *    is_top:    置顶
     *    update_time: "更新时间"
     * }
     * ]
     * }
     */
    public function editInfo()
    {
        $id = input('param.id', '', 'int');
        $info = Db::name('Application')->where(['id'=>$id])->find();
        if ($info) {
            $this->response('1', '操作成功', $info);
        } else {
            $this->response('1', '暂无数据', $info);
        }
    }

    /**
     * @api {post} /data/Application/saveInfo 提交应用信息
     * @apiVersion 1.0.0
     * @apiName saveInfo
     * @apiGroup Application
     * @apiDescription 提交应用信息
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign        签名.
     * @apiParam {int}    id          应用ID.
     * @apiParam {String} term_type:  终端（多个','分割）.
     * @apiParam {String} cover_img:  封面（多个','分割）,
     * @apiParam {String} intro:      介绍,
     * @apiParam {int}    count       虚拟使用数,
     * @apiParam {int}    sort:       排序,
     * @apiParam {int}    is_top:     置顶

     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveInfo()
    {
        $param = input('post.');
//        dd($param);
        $param['update_time'] = time();
        $info = Db::name('Application')->where(['id'=>$param['id']])->update($param);
        if ($info) {
            $this->response('1', '操作成功', $info);
        } else {
            $this->response('1', '暂无数据', $info);
        }
    }


    public function termType()
    {
        $type = [
            '1'=>'APP'
        ];
        return $type;
    }

    /**
     * @api {post} /data/Application/termTypeList 获取应用类型列表
     * @apiVersion 1.0.0
     * @apiName termTypeList
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
     *      id: "1",
     *      name: "APP"
     *  }
     *  ]
     *  }
     */
    public function termTypeList()
    {
        $info = $this->termType();
        $infos = '';
        foreach ($info as $key => $value) {
            $infos[$key]['id'] = (string)$key;
            $infos[$key]['name'] = $value;
        }
        if ($infos) {
            $this->response('1', '操作成功', $infos);
        } else {
            $this->response('1', '暂无数据', $infos);
        }
    }

    public function addViewNumber()
    {
        $id = input('id', '', 'intval');
        $res = DB::name('Application')->where(['id'=>$id])->setInc('number');
        return $res;
    }


}
