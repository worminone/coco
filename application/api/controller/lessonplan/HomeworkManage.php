<?php

namespace app\api\controller\lessonplan;

use \app\lessonplan\model\Homework;
use app\common\controller\Api;
use think\Db;

class HomeworkManage extends Api
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @api {post} /api/HomeworkManage/getHomeworkInfo 查询作业详情(包含附件)
     * @apiVersion 1.0.0
     * @apiName getHomeworkInfo
     * @apiGroup HomeworkManageApi
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
     * "description":"",//描述
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
     * @api {post} /api/HomeworkManage/getHomeworkRourse 查询作业附件
     * @apiVersion 1.0.0
     * @apiName getHomeworkRourse
     * @apiGroup HomeworkManageApi
     * @apiDescription 查询作业详情(包含附件)
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} list 作业附件
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getHomeworkRourse()
    {
        $str = trim(input('param.list'),',');
        $where['id'] = ['in', $str];
        $where['file_type'] = ['gt', 1];
        $list = Db::name('admin_upload')->where($where)->select();
        $this->response(1, '成功', $list);
    }
}
