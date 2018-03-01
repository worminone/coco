<?php

namespace app\lessonplan\controller;

use \app\lessonplan\model\TeachingContent;
use \app\lessonplan\model\Homework;
use app\common\controller\Base;
use app\common\controller\Admin;

class StudentApp extends Base
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * @api {post} /lessonplan/StudentApp/getPic 课堂配图
     * @apiVersion 1.0.0
     * @apiName getPic
     * @apiGroup StudentApp
     * @apiDescription 课堂配图
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": {
     * "title": "给我一个为你改变的理由",
     * "pic": "http://student.api.zgxyzx.net/H5/image/question.png"
     * }
     * }
     */
    public function getPic()
    {
        $data['title'] = '给我一个为你改变的理由';
        $data['pic'] = 'http://'.$_SERVER['SERVER_NAME'].'/logo.png';
        $this->response(1, '获取成功', $data);
    }
    /**
     * @api {post} /lessonplan/StudentApp/getList 根据目录ID获取内容列表
     * @apiVersion 1.0.0
     * @apiName getList
     * @apiGroup StudentApp
     * @apiDescription 根据目录ID获取内容列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} catalogue_id 目录ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "成功",
     * "data": [
     * {
     * "id": 4,
     * "catalogue_id": 2,
     * "content_type": 2,
     * "status": 1,
     * "upload_id": 4,
     * "title": "高中生生涯成熟度测评",
     * "cover": "",
     * "QRcode": "http://www.ja.com/public/lessonplan/QRManage/getQR?url=http://www.ja.com/public/lessonplan/QRManage/getInfo.php?id=4"
     * }
     * ]
     * }
     */
    public function getList()
    {
        $catalogue_id = input('catalogue_id', 0);
        if (empty($catalogue_id)) {
            $this->response(-1, '目录ID不能为空');
        }
        $teachingModel = new TeachingContent();
        $dataList = $teachingModel->getList($catalogue_id);
        $this->response(1, '成功', $dataList);
    }

    /**
     * @api {post} /lessonplan/StudentApp/getHomeworkInfo 查询作业详情
     * @apiVersion 1.0.0
     * @apiName getHomeworkInfo
     * @apiGroup StudentApp
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

    public function getInfo()
    {
        $id = input('id', 0, 'int');
        if (empty($id)) {
            $this->response(-1, 'ID不能为空');
        }
    }


}
