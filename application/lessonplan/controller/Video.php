<?php
namespace app\lessonplan\controller;

use app\common\controller\Admin;
use think\Db;
use app\common\controller\Base;

class Video extends Base
{
    /**
     * @api {post} /lessonplan/Video/getVideoList 获取视频列表
     * @apiVersion 1.0.0
     * @apiName getVideoList
     * @apiGroup Video
     * @apiDescription 获取视频列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} name 名称.
     * @apiParam {String} page 页码.
     * @apiParam {String} pageSize 每页条数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getVideoList()
    {
        $where = array();
        $pageSize = input('param.pageSize', 10);
        $name = trim(input('param.name', '', 'htmlspecialchars'));
        $page = input('param.page', 1);
        if (strlen($name)>0) {
            $where['file_name'] = ['like', '%'.$name.'%'];
        }
        if ($pageSize == 0) {
            $pageSize = 10;
        }
        $where['file_type'] = 3;
        $limit=$this->getLimit($page, $pageSize);
        $data['total'] = model('Video')->getVideoCount($where);
        $data['pageSize'] = intval($pageSize);
        $data['list'] = model('Video')->getVideoData($where, $limit);
        $this->response(1, '获取成功', $data);
    }
    /**
     * @api {post} /lessonplan/Video/addVideo 新增视频
     * @apiVersion 1.0.0
     * @apiName addVideo
     * @apiGroup Video
     * @apiDescription 新增视频
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} uid 管理员ID.
     * @apiParam {String} url 文件路径.
     * @apiParam {String} file_name 文件名称.
     * @apiParam {String} thumb 封面地址.
     * @apiParam {String} ext 文件的扩展名.
     * @apiParam {String} description 描述.
     * @apiParam {String} file_size 文件大小.
     * @apiParam {String} play_time 播放时间.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addVideo()
    {
        $data = input('param.');
        $save = array(
            'url'         => $data['url'],
            'file_name'   => $data['file_name'],
            'file_type'   => 3,
            'source_type' => 1,
            'thumb'       => $data['thumb'],
            'description' => $data['description'],
        );
/*        if ($data['file_size']<1024) {
            $save['file_size'] = $data['file_size'].'B';
        } elseif ($data['file_size'] < 1024*1024) {
            $save['file_size'] = round($data['file_size']/1024, 2).'KB';
        } elseif ($data['file_size'] < 1024*1024*1024) {
            $save['file_size'] = round($data['file_size']/(1024*1024), 2).'MB';
        } elseif ($data['file_size'] > 1024*1024*1024) {
            $save['file_size'] = round($data['file_size']/(1024*1024*1024), 2).'GB';
        }*/
        $save['file_size']= round($data['file_size']/(1024*1024), 2).'MB';
        Db::name('admin_upload')->insert($save);
        $this->response(1, '保存成功');
    }
    /**
     * @api {post} /lessonplan/Video/delVideo 删除视频
     * @apiVersion 1.0.0
     * @apiName delVideo
     * @apiGroup Video
     * @apiDescription 删除视频
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id ID.
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function delVideo()
    {
        $data = input('param.id');
        $flag = model('Video')->delupload($data);
        if ($flag==1) {
            $this->response(1, '删除成功');
        } else {
            $this->response(-1, '该资源已被使用，无法删除');
        }
    }
    /**
     * @api {post} /lessonplan/Video/videoDetail 视频详情
     * @apiVersion 1.0.0
     * @apiName videoDetail
     * @apiGroup Video
     * @apiDescription 视频详情
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id 资源ID
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function videoDetail()
    {
        $id = input('param.id');
        $detail = model('Video')->getVideoDetail($id);
        if (empty($detail)) {
            $this->response(-1, '无数据');
        } else {
            $this->response(1, '成功', $detail);
        }

    }
    /**
     * @api {post} /lessonplan/Video/updateVideo 编辑视频资料
     * @apiVersion 1.0.0
     * @apiName updateVideo
     * @apiGroup Video
     * @apiDescription 编辑视频资料
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id 资源ID
     * @apiParam {String} thumb 缩略图
     * @apiParam {String} file_name 文件名称
     * @apiParam {String} description 文件描述
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function updateVideo()
    {
        $data = input('param.');
        $where['id'] = $data['id'];
        $update=array(
            'thumb' => $data['thumb'],
            'file_name' => $data['file_name'],
            'description' => $data['description'],
        );
        Db::name('admin_upload')->where($where)->update($update);
        $this->response(1, '保存成功');
    }

    public function getLimit($page, $size)
    {
        $start = ($page-1)*$size;
        $limit = $start.','.$size;
        return $limit;
    }
}