<?php
namespace app\data\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class CollegeVideo extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /index/CollegeVideo/videoList 视频数据列表(后台)
     * @apiVersion                               1.0.0
     * @apiName                                  videoList
     * @apiGroup                                 CollegeVideo
     * @apiDescription                           视频数据列表(后台)
     *
     * @apiParam {String} token           用户的token.
     * @apiParam {String} time            请求的当前时间戳.
     * @apiParam {String} sign            签名.
     * @apiParam {int} page               当前页数.
     * @apiParam {int} pagesize           页数
     * @apiParam {int} college_id         院校ID
     * @apiParam {int} class_id           类型ID(1大学 2 专业)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      video_id: 视频ID,
     *      title: "标题",
     *      video_img: "视频图片",
     *      video_url: "视频地址",
     *      create_time: "发布时间"
     * }
     * ]
     * }
     *
     */
    public function videoList()
    {
        $class_id = input('param.class_id', '', 'intval');
        $college_id = input('param.college_id', '', 'intval');
        $pagesize = input('param.pagesize', '10', 'int');
        $where['c_id'] = $college_id;
        if (!empty($class_id)) {
            $where['college_type'] = $class_id;
        }
        if (!empty($class_id)) {
            $where['college_type'] = $class_id;
        }
        $field = 'id as video_id,title,cover as video_img,content as video_url,
        initial_size,duration,college_id,college_type,create_time,c_id';
        $list = $this->getPageList('Video', $where, 'id desc', $field, $pagesize);
        foreach ($list['list'] as $key => $value) {
            if($value['college_type'] == 2 ) {
                $admin_key = config('admin_key');
                $college_api = config('college_api');
                $url = $college_api . '/index/Major/getMajorInfo';
                $param['type_id'] = $value['college_id'];
                $param['college_id'] = $value['c_id'];
                $param['admin_key'] = $admin_key;
                $data = curl_api($url, $param, 'post');
                if ($data['code'] == -1) {
                    $list['list'][$key]['major_name'] = '';
                } else {
                    $list['list'][$key]['major_name'] = $data['data']['majorName'];
                }
            } else {
                $list['list'][$key]['major_name'] = '';
            }
        }
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /index/CollegeVideo/getCollegeMajor 获取该大学专业列表(后台)
     * @apiVersion                                     1.0.0
     * @apiName                                        videoList
     * @apiGroup                                       getCollegeMajor
     * @apiDescription                                 获取该大学专业列表(后台)
     *
     * @apiParam {String} token             用户的token.
     * @apiParam {String} time              请求的当前时间戳.
     * @apiParam {String} sign              签名.
     * @apiParam {int} college_id           院校ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id:  id,
     *      college_id: 院校ID,
     *      majorNumber: 专业代码
     *      majorName: 专业名称,
     *      major_id: 专业id
     * }
     * ]
     * }
     *
     */
    public function getCollegeMajor()
    {
        $college_id  = input('param.college_id', '', 'intval');
        $admin_key = config('admin_key');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeVideo/getCollegeMajor';
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /data/CollegeVideo/addVideo 添加视频数据（后台）
     * @apiVersion                              1.0.0
     * @apiName                                 addVideo
     * @apiGroup                                CollegeVideo
     * @apiDescription                          添加视频数据（后台）
     *
     * @apiParam {String}        token 用户的token.
     * @apiParam {String}        time 请求的当前时间戳.
     * @apiParam {String}        sign 签名.
     * @apiParam {String}        video_img:视频图片
     * @apiParam {Int}           class_id:视频类型  (1大学视频 2专业视频 )
     * @apiParam {Int}           college_id:院校ID 
     * @apiParam {Int}           major_id:专业ID(专业才会有) 
     * @apiParam {String}        video_url:视频地址
     * @apiParam {String}        title:标题
     * @apiParam {String}        intro:介绍
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addVideo()
    {
        $param = input('param.');
        $param['cover'] = $param['video_img'];
        $param['content'] = $param['video_url'];
        $param['description'] = $param['intro'];
        $param['college_type'] = $param['class_id'];
        $param['c_id'] = $param['college_id'];
        if( $param['class_id'] == 1) {
            $param['college_id'] = $param['college_id'];
        } else {
            $param['college_id'] = $param['major_id'];
        }
        $res = DB::name('Video')->insert($param);
        if ($res) {
            $this->response('1', '操作成功');
        } else {
            $this->response('-1', '操作失败');
        }
    }


    /**
     * @api {post} /index/CollegeVideo/editVideo 编辑视频数据（后台）
     * @apiVersion                               1.0.0
     * @apiName                                  editVideo
     * @apiGroup                                 CollegeVideo
     * @apiDescription                           编辑视频数据（后台）
     *
     * @apiParam {String}             token 用户的token.
     * @apiParam {String}             time 请求的当前时间戳.
     * @apiParam {String}             sign 签名.
     * @apiParam {String}             video_img:视频图片
     * @apiParam {Int}                class_id:视频类型  (1大学视频 2专业视频 )
     * @apiParam {Int}                college_id:院校ID 
     * @apiParam {Int}                major_id:专业ID(专业才会有) 
     * @apiParam {String}             video_url:视频地址
     * @apiParam {String}             title:标题
     * @apiParam {String}             intro:介绍
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息..
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      video_id: 视频ID,
     *      title: "标题",
     *      video_img: "视频图片",
     *      video_url: "视频地址",
     *      create_time: "发布时间"
     *      intro:"介绍"
     * }
     * ]
     * }
     *
     */
    public function editVideo()
    {
        $param['id'] = input('param.video_id');
        $field = 'id as video_id,c_id,college_type as class_id,college_id,title,cover as video_img,
        content as video_url,initial_size,duration,description as intro,create_time';
        $info = Db::name('Video')->field($field)->where($param)->find();

        if($info['class_id'] == 2 ) {
            $admin_key = config('admin_key');
            $college_api = config('college_api');
            $url =  $college_api.'/index/Major/getMajorInfo';
            $param['type_id'] = $info['college_id'];
            $param['college_id'] = $info['c_id'];
            $param['admin_key'] = $admin_key;
            $data = curl_api($url, $param, 'post');
            if($data['code'] == -1) {
                $info['major_top_number'] = '';
                $info['major_type_number'] = '';
                $info['major_number'] = '';
                $info['major_id'] = '';
            } else {
                $major_number = $data['data']['majorNumber'];
                $info['major_top_number'] = substr($major_number, 0 , 2);
                $info['major_type_number'] = substr($major_number, 0 , 4);
                $info['major_number'] = $major_number;
                $info['major_id'] = $info['college_id'];
            }
            $info['college_id'] = $info['c_id'];
        }
        if ($info) {
            $this->response('1', '操作成功',$info);
        } else {
            $this->response('-1', '操作失败');
        }
    }

    /**
     * @api {post} /index/CollegeVideo/saveVideo 提交修改视频数据（后台）
     * @apiVersion                              1.0.0
     * @apiName                                 saveVideo
     * @apiGroup                                CollegeVideo
     * @apiDescription                          提交修改视频数据（后台）
     *
     * @apiParam {String}        token 用户的token.
     * @apiParam {String}        time 请求的当前时间戳.
     * @apiParam {String}        sign 签名.
     * @apiParam {String}        video_img:视频图片
     * @apiParam {Int}           class_id:视频类型  (1大学视频 2专业视频 )
     * @apiParam {Int}           college_id:院校ID 
     * @apiParam {Int}           major_id:专业ID(专业才会有) 
     * @apiParam {String}        video_url:视频地址
     * @apiParam {String}        title:标题
     * @apiParam {String}        intro:介绍
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function saveVideo()
    {
        $param = input('param.');
        $param['cover'] = $param['video_img'];
        $param['id'] = $param['video_id'];
        $param['content'] = $param['video_url'];
        $param['description'] = $param['intro'];
        $param['college_type'] = $param['class_id'];
        $param['c_id'] = $param['college_id'];
        if( $param['class_id'] == 1) {
            $param['college_id'] = $param['college_id'];
        } else {
            $param['college_id'] = $param['major_id'];
        }
        $res = Db::name('Video')->update($param);
        if ($res !==false) {
            $this->response('1', '操作成功');
        } else {
            $this->response('-1', '操作失败');
        }
    }

    
     /**
     * @api {get} /index/CollegeVideo/deleteVideo 视频删除操作
     * @apiVersion 1.0.0
     * @apiName deleteVideo
     * @apiGroup CollegeVideo
     * @apiDescription 视频删除操作
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} video_id:视频id (多个用‘,’分割)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function deleteVideo()
    {
        $id = input('param.video_id');
        if (empty($id)) {
            $this->response('-1', '参数不能为空');
        }
        $where['id'] = ['in', $id];
        $res = DB::name('Video')->where($where)->delete();
        if ($res) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }


}
