<?php

namespace app\lessonplan\controller;

use app\common\controller\Base;
use app\common\controller\Admin;
use think\Db;
use app\lessonplan\model\AdminUpload;
use app\lessonplan\model\Homework;
use app\lessonplan\model\TeachingContent;

class TeachingManage extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @api {post} /lessonplan/TeachingManage/getList 获取内容列表
     * @apiVersion 1.0.0
     * @apiName getList
     * @apiGroup TeachingManage
     * @apiDescription 获取内容列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} catalogue_id 目录ID.
     * @apiParam {Int} content_type 内容形式ID，1:视频,2:量表,3:作业,4:课件,5:资讯.(可选 默认返回全部类型)
     * @apiParam {Int} type 0 显示全部 1 显示有内容 默认 0
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
     * "file_type":"",//文件类型 1:图片,2:文档,3:视频 4 量表 5资讯 6 作业
     * }
     * ]
     * }
     */
    public function getList()
    {
        $catalogue_id = input('catalogue_id', 0);
        $content_type = input('content_type', 0);
        $type = input('type', 0);
        $newDataList = array();
        //文件类型，1:图片,2:文档,3:视频
        //内容形式ID，1:视频,2:量表,3:作业,4:课件,5:资讯
        $changeType = array(1 => 3, 2 => 4, 3 => 6, 4 => 2, 5 => 5);
        if (empty($catalogue_id)) {
            $this->response(-1, '目录ID不能为空');
        }
        $teachingModel = new TeachingContent();
        $dataList = $teachingModel->getList($catalogue_id, $content_type,3);
        if (empty($dataList)) {
            $dataList = array();
        }
        foreach ($dataList as &$value) {
            $value['file_type'] = !empty($changeType[$value['content_type']]) ? $changeType[$value['content_type']] : 0;
        }
        if (!empty($type)) {
            foreach ($dataList as $key => $value2) {
                if ($value2['file_type'] == 3) {
                    $value2['video_type'] = 'neirong';
                }
                if (!empty($value2['content_type'])) {
                    $newDataList[] = $value2;
                }
            }
            $dataList = $newDataList;
        }

        $this->response(1, '成功', $dataList);
    }

    /**
     * @api {post} /lessonplan/TeachingManage/delInfo 删除内容
     * @apiVersion 1.0.0
     * @apiName delInfo
     * @apiGroup TeachingManage
     * @apiDescription 删除内容
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} id 内容ID(批量用","隔开)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function delInfo()
    {
        $id = input('id', 0);
        if (empty($id)) {
            $this->response(-1, 'ID不能为空');
        }
        $teachingModel = new TeachingContent();
        $flag = $teachingModel->delInfo($id);
        if ($flag) {
            $this->response(1, '删除成功');
        } else {
            $this->response(-1, '该资源已被使用，无法删除');
        }
    }

    /**
     * @api {post} /lessonplan/TeachingManage/getResourceList 根据类型得到相关资源
     * @apiVersion 1.0.0
     * @apiName getResourceList
     * @apiGroup TeachingManage
     * @apiDescription 根据类型得到相关资源
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} type 类型 1:视频,2:量表,3:作业,4:课件,5:资讯
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     *{
     * "code": 1,
     * "msg": "成功",
     * "data": [
     * {
     * "id": "1",
     * "title": "MBTI职业性格测试"
     * },
     * {
     * "id": "3",
     * "title": "兴趣倾向测试"
     * },
     * {
     * "id": "2",
     * "title": "职业能力倾向测试"
     * },
     * {
     * "id": "4",
     * "title": "高中生生涯成熟度测试"
     * },
     * {
     * "id": "5",
     * "title": "青春指向标"
     * }
     * ]
     * }
     */
    public function getResourceList()
    {
        $type = input('type', 0);
        $type_id = input('type_id', 3);
        if (empty($type)) {
            $this->response(-1, '请选择类型');
        }
        $dataList = array();
        switch ($type) {
            case 1://视频
                $model = new AdminUpload();
                $where['file_type'] = 3;
                $data = $model->getList($where, 'ID DESC', '0,9999');
                if (!empty($data['list'])) {
                    foreach ($data['list'] as $value) {
                        $dataList[] = array('id' => $value['id'], 'title' => $value['file_name']);
                    }
                }
                break;
            case 2://量表
                $model = new TeachingContent();
                $data = $model->getTestList($type_id);
                if (!empty($data['data'])) {
                    foreach ($data['data'] as $value) {
                        $dataList[] = array('id' => $value['type_id'], 'title' => $value['title']);
                    }
                }
                break;
            case 3://作业
                $model = new Homework();
                $where = array();
                $data = $model->getList($where, 'ID DESC', '0,9999');
                if (!empty($data['list'])) {
                    foreach ($data['list'] as $value) {
                        $dataList[] = array('id' => $value['id'], 'title' => $value['description']);
                    }
                }
                break;
            case 4://课件
                $model = new AdminUpload();
                $where['file_type'] = 2;
                $data = $model->getList($where, 'ID DESC', '0,9999');
                if (!empty($data['list'])) {
                    foreach ($data['list'] as $value) {
                        $dataList[] = array('id' => $value['id'], 'title' => $value['file_name']);
                    }
                }
                break;
            case 5://资讯
                $model = new TeachingContent();
                $data = $model->getArticleList();
                if (!empty($data['data']['list'])) {
                    foreach ($data['data']['list'] as $value) {
                        $dataList[] = array('id' => $value['id'], 'title' => $value['title']);
                    }
                }
                break;
            default:
                break;
        }
        $this->response(1, '成功', $dataList);
    }

    /**
     * @api {post} /lessonplan/TeachingManage/getResourceData 根据类型得到相关资源详情
     * @apiVersion 1.0.0
     * @apiName getResourceData
     * @apiGroup TeachingManage
     * @apiDescription 根据类型得到相关资源详情
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} id ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getResourceData()
    {
        $where['id'] = input('param.id', 0);
        if ($where['id'] == 0) {
            $this->response(-1, '参数错误');
        }
        $dataList = array();
        $data = Db::name('teaching_content')->where($where)->find();
        $where['id'] = $data['upload_id'];
        switch ($data['content_type']) {
            case 1:
                $dataList = Db::name("admin_upload")->where($where)->find();
                break;
            case 2:
                $model = new TeachingContent();
                $dataList = $model->getTestDetail($where['id']);
                break;
            case 3:
                $dataList = Db::name("homework")->where($where)->find();
                break;
            case 4:
                $dataList = Db::name("admin_upload")->where($where)->find();
                break;
            case 5:
                $model = new TeachingContent();
                $dataList = $model->getArticleInfo($where['id']);
                break;
            default:
                break;
        }
        $this->response(1, '成功', $dataList);
    }

    /**
     * @api {post} /lessonplan/TeachingManage/addInfo 添加内容
     * @apiVersion 1.0.0
     * @apiName addInfo
     * @apiGroup TeachingManage
     * @apiDescription 添加内容
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} id 内容ID（可选 不为空则为修改）
     * @apiParam {Int} catalogue_id 目录ID
     * @apiParam {Int} content_type  内容类型（content_type 内容形式ID，1:视频,2:量表,3:作业,4:课件,5:资讯）
     * @apiParam {Int} upload_id 资源ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addInfo()
    {
        $id = input('id', 0);
        $data['catalogue_id'] = input('catalogue_id', '');
        $data['content_type'] = input('content_type', '');
        $data['upload_id'] = input('upload_id', '');

        if (empty($data['catalogue_id'])) {
            $this->response(-1, '目录ID不能为空');
        }
        $teachModel = new TeachingContent();
        if (!empty($id)) {
            $flag = $teachModel->editInfo($id, $data);
            if (!$flag) {
                $this->response(-1, '修改成功');
            } else {
                $this->response(1, '修改成功', $flag);
            }
        } else {
            $flag = $teachModel->addInfo($data);
            if (!$flag) {
                $this->response(-1, '添加失败');
            } else {
                $this->response(1, '添加成功', $flag);
            }
        }
    }

    /**
     * @api {post} /lessonplan/TeachingManage/editInfo 修改内容
     * @apiVersion 1.0.0
     * @apiName editInfo
     * @apiGroup TeachingManage
     * @apiDescription 修改内容
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} id 内容ID
     * @apiParam {Int} catalogue_id 目录ID
     * @apiParam {Int} content_type  内容类型（content_type 内容形式ID，1:视频,2:量表,3:作业,4:课件,5:资讯）
     * @apiParam {Int} upload_id 资源ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function editInfo()
    {
        $id = input('id', 0);
        $data['catalogue_id'] = input('catalogue_id', '');
        $data['content_type'] = input('content_type', '');
        $data['upload_id'] = input('upload_id', '');

        $teachModel = new TeachingContent();
        $flag = $teachModel->editInfo($id, $data);
        if (!$flag) {
            $this->response(-1, '添加失败');
        } else {
            $this->response(1, '添加成功', $flag);
        }
    }

    /**
     * @api {post} /lessonplan/TeachingManage/exportQR
     * @apiVersion 1.0.0
     * @apiName exportQR
     * @apiGroup TeachingManage
     * @apiDescription 批量导出二维码
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function exportQR()
    {
        $teachModel = new TeachingContent();
        $teachModel->exportWord();
    }
    /**
     * @api {post} /lessonplan/TeachingManage/webEvaluateList web端已配置测评列表
     * @apiVersion 1.0.0
     * @apiName webEvaluateList
     * @apiGroup TeachingManage
     * @apiDescription web端已配置测评列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {Int} id 内容ID
     * @apiParam {Int} catalogue_id 目录ID
     * @apiParam {Int} content_type  内容类型（content_type 内容形式ID，1:视频,2:量表,3:作业,4:课件,5:资讯）
     * @apiParam {Int} upload_id 资源ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function webEvaluateList()
    {
        $where['content_type'] = 2;
        $list = Db::name('teaching_content')->distinct(true)->where($where)->column('upload_id');
        $this->response(1, '添加成功', $list);
    }
}
