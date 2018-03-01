<?php

namespace app\api\controller\lessonplan;

use app\common\controller\Api;
use app\lessonplan\model\TeachingContent;
use think\Db;

class TeachingManage extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @api {post} /api/TeachingManage/getList 获取内容列表
     * @apiVersion 1.0.0
     * @apiName getList
     * @apiGroup TeachingManageApi
     * @apiDescription 获取内容列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} catalogue_id 目录ID.
     * @apiParam {Int} content_type 内容形式ID，1:视频,2:量表,3:作业,4:课件,5:资讯.(可选 默认返回全部类型)
     * @apiParam {Int} type  (0 旧App,1新App,2一体机,3.新高中三端）
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
     * "catalogue_id": 1,
     * "content_type": 2,
     * "status": 1,
     * "upload_id": 4,
     * "title": "高中生生涯成熟度测评",
     * "QRcode": "http://www.ja.com/public/lessonplan/QRManage/getQR?url=http://www.ja.com/public/lessonplan/QRManage/getInfo?id=4",//二维码地址
     * "cover":"",//封面图
     * }
     * ]
     * }
     */
    public function getList()
    {
        $catalogue_id = input('catalogue_id', 0);
        $content_type = input('content_type', 0);
        $type = input('type', 1);
        if (empty($catalogue_id)) {
            $this->response(-1, '目录ID不能为空');
        }
        $newDataList = array();
        $teachingModel = new TeachingContent();
        $dataList = $teachingModel->getList($catalogue_id, $content_type, $type);
        if (!empty($dataList)) {
            foreach ($dataList as $key => $value) {
                if (!empty($value['content_type'])) {
                    $newDataList[] = $value;
                }
            }
        }
        $this->response(1, '成功', $newDataList);
    }

    /**
     * @api {post} /api/TeachingManage/getInfo 根据id查询内容信息
     * @apiVersion 1.0.0
     * @apiName getInfo
     * @apiGroup TeachingManageApi
     * @apiDescription 根据id查询内容信息
     *
     * @apiParam {Int} id 内容ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getInfo()
    {
        $lastData = array();
        $id = input('id');
        $teachingModel = new TeachingContent();
        $data = $teachingModel->getUploadInfo($id);
        if ($data) {
            $lastData['file_type'] = $data['file_type'];
            $lastData['upload_id'] = $data['id'];
            $lastData['url'] = !empty($data['url']) ? $data['url'] : '';

            $lastData['title'] = !empty($data['file_name']) ? $data['file_name'] : '';
            $lastData['cover'] = !empty($data['thumb']) ? $data['thumb'] : '';
            $lastData['description'] = !empty($data['description']) ? $data['description'] : '';
            $lastData['create_time'] = !empty($data['create_time']) ? $data['create_time'] : '';

            $this->response(1, '获取成功', $lastData);
        } else {
            $this->response(-1, '获取失败');
        }
    }
    /**
     * @api {post} /api/TeachingManage/getEvaluateList 根据school_id查询测评列表
     * @apiVersion 1.0.0
     * @apiName getEvaluateList
     * @apiGroup TeachingManageApi
     * @apiDescription 根据school_id查询测评列表
     *
     * @apiParam {Int} school_id 学校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getEvaluateList()
    {
        $where['school_id'] = input('param.school_id', 0);
        $combo['id'] = Db::name('teaching_sale')->where($where)->value('combo_id');
        $content = Db::name('teaching_combo')->where($combo)->value('chapter_arr');
        $con_where['catalogue_id'] = ['in', trim($content, ',')];
        $con_where['content_type'] = 2;
        $con_where['upload_id'] = ['gt', 0];
        $list = Db::name('teaching_content')->distinct(true)->where($con_where)->field('upload_id')->select();
        $this->response(1, '获取成功', $list);
    }
}
