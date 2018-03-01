<?php

namespace app\lessonplan\controller;

use \app\lessonplan\model\Homework;
use app\common\controller\Base;
use app\common\controller\Admin;

class HomeworkManage extends Base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @api {post} /lessonplan/HomeworkManage/getList 查询作业列表
     * @apiVersion 1.0.0
     * @apiName getList
     * @apiGroup HomeworkManage
     * @apiDescription 查询作业列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} keyword 搜索关键字.
     * @apiParam {Int} page 页号,默认1.
     * @apiParam {Int} pageSize 页大小,默认20.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "成功",
     * "data": {
     * "total": 1,
     * "list": [
     * {
     * "id": 1,
     * "description": "作业描述",
     * "cover": "http：//12312312312312312312",//封面
     * }
     * ]
     * }
     * }
     */
    public function getList()
    {
        $keyword = trim(input('keyword', ""));
        $page = input('page', 1);
        $pageSize = input('pageSize', 10);
        if (empty($pageSize)) {
            $pageSize = 10;
        }

        $limit = getLimit($page, $pageSize);
        $homeworkModel = new Homework();
        $where = '';
        if ($keyword != '') {
            $where['description'] = array('like', '%' . $keyword . '%');
        }
        $dataList = $homeworkModel->getList($where, $order = 'id DESC', $limit);
        $dataList['page'] = $page;
        $dataList['pageSize'] = intval($pageSize);
        if ($dataList) {
            $this->response(1, '成功', $dataList);
        } else {
            $this->response(-1, '查询失败');
        }
    }

    /**
     * @api {post} /lessonplan/HomeworkManage/delInfo 批量删除作业
     * @apiVersion 1.0.0
     * @apiName delInfo
     * @apiGroup HomeworkManage
     * @apiDescription 批量删除作业
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} id 作业ID(批量用","隔开)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "删除成功"
     * }
     */
    public function delInfo()
    {
        $id = input('id', 0);
        if (empty($id)) {
            $this->response(-1, 'ID不能为空');
        }
        $homeworkModel = new Homework();
        $flag = $homeworkModel->delInfo($id);
        if ($flag) {
            $this->response(1, '删除成功');
        } else {
            $this->response(-1, '该资源已被使用，无法删除');
        }
    }

    /**
     * @api {post} /lessonplan/HomeworkManage/addInfo 添加作业
     * @apiVersion 1.0.0
     * @apiName addTeacherInfo
     * @apiGroup HomeworkManage
     * @apiDescription 添加作业
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} description 作业描述
     * @apiParam {String} cover 作业封面
     * @apiParam {String} content 作业内容
     * @apiParam {int} answer_type 回答方式，1:文字,2:图片,3:图文
     * @apiParam {Array} resource 作业的相关资源 如 array(1,2);
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addInfo()
    {
        $param = input('param.');
        $contentData = $param['data'];
        $data['description'] = $contentData['description'];
        $data['cover'] = $contentData['cover'];
        !empty($contentData['content']) && $data['content'] = $contentData['content'];
        !empty($contentData['answer_type']) && $data['answer_type'] = $contentData['answer_type'];
        !empty($contentData['id']) && $id = $contentData['id'];
        $resource = !empty($param['id']) ? $param['id'] : '';
        if (!empty($resource) && is_array($resource)) {
            $resource = ',' . implode(',', $resource) . ',';
        }
        $data['resource'] = $resource;

        $homeworkModel = new Homework();
        if (!empty($id)) {
            $flag = $homeworkModel->editInfo($id, $data);
            if (!$flag) {
                $this->response(1, '修改成功');
            } else {
                $this->response(1, '修改成功', $flag);
            }
        } else {
            $flag = $homeworkModel->addInfo($data);
            if (!$flag) {
                $this->response(-1, '添加失败');
            } else {
                $this->response(1, '添加成功', $flag);
            }
        }
    }

    /**
     * @api {post} /lessonplan/HomeworkManage/getInfo 查询作业详情
     * @apiVersion 1.0.0
     * @apiName getInfo
     * @apiGroup HomeworkManage
     * @apiDescription 查询作业详情
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} id 作业ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "成功",
     * "data": {
     * "id": 4,
     * "description": "作业描述",//作业描述
     * "content": "作业内容",//作业内容
     * "answer_type": 1,
     * "resource": "1,2,",
     * "cover": "http：//12312312312312312312"//封面
     * }
     * }
     */
    public function getInfo()
    {
        $id = input('id', 0, 'int');
        if (empty($id)) {
            $this->response(-1, 'ID不能为空');
        }
        $homeworkModel = new Homework();
        $dataInfo = $homeworkModel->getInfoById($id);
        if ($dataInfo) {
            $this->response(1, '成功', $dataInfo);
        } else {
            $this->response(-1, '查询失败');
        }
    }

    /**
     * @api {post} /lessonplan/HomeworkManage/getHomeworkInfo 查询作业详情(包含附件)
     * @apiVersion 1.0.0
     * @apiName getHomeworkInfo
     * @apiGroup HomeworkManage
     * @apiDescription 查询作业详情(包含附件)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} id 作业ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "成功",
     * "data": {
     * "id": 4,
     * "description": "作业描述",
     * "content": "作业内容",
     * "answer_type": "文字",//回答方式，1:文字,2:图片,3:图文
     * "resource": "7,8,",
     * "cover": "",//封面url地址
     * "resourceList": [
     * {
     * "id": 7,
     * "url": "http://orvv6n9w4.bkt.clouddn.com/FgNfw0_m1jz9qexYDkq1VDJL8Vu0",//文件的访问路径
     * "file_name": "inbox",//文件名
     * "file_type": 3,//文件类型，1:图片,2:文档,3:视频
     * "thumb": "",//缩略图或者是视频的封面
     * "ext": "png"//文件的扩展名
     * }
     * ]
     * }
     * }
     */
    public function getHomeworkInfo()
    {
        $id = input('id', 0, 'int');
        if (empty($id)) {
            $this->response(-1, 'ID不能为空');
        }
        $homeworkModel = new Homework();
        $dataInfo = $homeworkModel->getInfoResourceById($id);
        if ($dataInfo) {
            $this->response(1, '成功', $dataInfo);
        } else {
            $this->response(-1, '查询失败');
        }
    }

    /**
     * @api {post} /lessonplan/HomeworkManage/getResource 根据作业ID查询资源列表
     * @apiVersion 1.0.0
     * @apiName getResource
     * @apiGroup HomeworkManage
     * @apiDescription 根据作业ID查询资源列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} id 作业ID. 为0则输出全部未选中资源
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "成功",
     * "data": {
     * "1": [
     * {
     * "id": 12,
     * "url": "http://orvv6n9w4.bkt.clouddn.com/Fp5QYqN3WG_z1Rp9NE5Ctu1PUd7-",//文件的访问路径
     * "thumb": "",//缩略图或者是视频的封面
     * "file_name": "wallpaper-1rra",//文件名
     * "file_type": 1,//文件类型，1:图片,2:文档,3:视频
     * "hidden": true//是否选中 true 未选中 false 已选中
     * },
     * }
     */
    public function getResource()
    {
        $id = input('id', 0, 'int');
        $homeworkModel = new Homework();
        $dataList = $homeworkModel->getResource($id);
        $this->response(1, '成功', $dataList);
    }

    /**
     * @api {post} /lessonplan/HomeworkManage/editInfo 修改作业详情
     * @apiVersion 1.0.0
     * @apiName editInfo
     * @apiGroup HomeworkManage
     * @apiDescription 修改作业详情
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} id 作业ID.
     * @apiParam {String} description 作业描述
     * @apiParam {String} cover 作业封面
     * @apiParam {String} content 作业内容
     * @apiParam {int} answer_type 回答方式，1:文字,2:图片,3:图文
     * @apiParam {Array} resource 作业的相关资源 如 array(1,2);
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function editInfo()
    {
        $param = input('param.');
        $contentData = $param['data'];
        $data['description'] = $contentData['description'];
        $data['cover'] = $contentData['cover'];
        $data['content'] = $contentData['content'];
        $data['answer_type'] = $contentData['answer_type'];
        $id = $contentData['id'];
        $resource = $param['id'];
        if (!empty($resource) && is_array($resource)) {
            $resource = implode(',', $resource) . ',';
        }
        $data['resource'] = $resource;
        $homeworkModel = new Homework();
        $flag = $homeworkModel->editInfo($id, $data);
        if (!$flag) {
            $this->response(1, '添加成功');
        } else {
            $this->response(1, '添加成功', $flag);
        }

    }
}
