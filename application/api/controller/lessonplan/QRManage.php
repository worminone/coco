<?php

namespace app\api\controller\lessonplan;

use app\common\controller\Api;
use app\lessonplan\model\TeachingContent;

class QRManage extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @api {post} /api/QRManage/getInfo 根据id查询内容信息
     * @apiVersion 1.0.0
     * @apiName getInfo
     * @apiGroup QRManageApi
     * @apiDescription 根据id查询内容信息
     *
     * @apiParam {Int} id 内容ID.
     * @apiParam {Int} school_id 学校ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": {
     * "content_type": 4,//内容形式ID，1:视频,2:量表,3:作业,4:课件,5:资讯
     * "upload_id": 196,//资源对应ID
     * "url":http://image.zgxyzx.net/FgwxtVKAIrwojDN1pCK0_2VTEjAH//资源对应地址
     * }
     * }
     */
    public function getInfo()
    {
        $lastData = array();
        $id = input('id');
        $school_id = input('school_id');
        if (empty($id) || empty($school_id)) {
            $this->response(-1, '参数错误');
        }
        $teachingModel = new TeachingContent();
        $data = $teachingModel->getInfo($id, $school_id);
        if ($data) {
            if ($data == -1) {
                $this->response(-1, '我校暂未购买该主题在线教案');
            }
            if (empty($data['content_type'])) {
                $this->response(-1, '教案内容为空，可能跑火星去了~');
            }
            $lastData['content_type'] = $data['content_type'];
            $lastData['upload_id'] = $data['upload_id'];
            $lastData['url'] = !empty($data['url']) ? $data['url'] : '';

            $lastData['title'] = !empty($data['file_name']) ? $data['file_name'] : '';
            $lastData['cover'] = !empty($data['thumb']) ? $data['thumb'] : '';
            $lastData['description'] = !empty($data['description']) ? $data['description'] : '';

            $this->response(1, '获取成功', $lastData);
        } else {
            $this->response(-1, '获取失败');
        }
    }
}