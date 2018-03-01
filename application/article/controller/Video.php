<?php
namespace app\article\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Video extends Admin
{   
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /article/Video/videoList 查看视频列表
     * @apiVersion              1.0.0
     * @apiName                 videoList
     * @apiGROUP                Video
     * @apiDescription          查看视频列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       title 标题名称（可选）
     * @apiParam {int}          status   状态（1：正常，0：禁用）可选
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {String}       num 分页数（可选）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *       id      ID
     *       cover   封面，列表页图片url地址   
     *       title   标题
     *       c_name  分类
     *       sort    排序
     *       t_name  终端
     *       is_top  是否置顶
     *       status  状态
     *       create_time  创建日期
     *       play_type   视频类型  1 本地 3 外链
     *       content 视频地址
     * }
     * ]
     * }
     *
     */
    public function videoList()
    {
        $title = input('param.title');
        $status = input('param.status', '-1', 'int');
        $where = '';
        if($status>=0) {
            $where['status'] = $status;
        }
        $pagesize = input('param.pagesize', '10', 'int');
        if(!empty($title)) {
            $where['title'] = ['like', "%".$title."%"];
        }
        $field = 'id,title,is_top,cover,sort,status,category_id,create_time,content,term_type,play_type,content';
         //过滤分类数据
        $c_where['status'] = 1;
        $cids = model('Category')->getCategoryTermIds($c_where);
        $where['category_id'] = ['in', $cids];
        $list = $this->getPageList('Video', $where, 'id desc', $field, $pagesize);
        foreach ($list['list'] as $key => $value) {
            $t_info = model('Term')->getTermType($value['term_type']);
            $c_info = model('Category')->getCategoryInfo($value['category_id']);
            $is_top = ['否', '是'];
            $list['list'][$key]['is_top'] = $is_top[$value['is_top']];
            $list['list'][$key]['t_name'] = $t_info['name'];
            $list['list'][$key]['c_name'] = $c_info['catrgory_top_name'];

        }
        if ($list['count'] > 0) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /article/Video/addVideo 添加视频
     * @apiVersion              1.0.0
     * @apiName                 addVideo
     * @apiGROUP                Video
     * @apiDescription          添加视频
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          category_id   分类ID
     * @apiParam {Int}          college_type  类型ID(1 大学 2 专业 3职业)
     * @apiParam {Int}          college_id  关联职业专业或者院校的视频ID
     * @apiParam {Int}          term_type   终端类型
     * @apiParam {String}       cover   封面，列表页图片url地址   
     * @apiParam {Int}          play_type   视频源来源地址,1:本地上传,2:iframe网页来源,3:外链视频源地址
     * @apiParam {String}       content   如果是play_type是1的话，就是admin_upload的id，不是就是网页或者视频源地址
     * @apiParam {String}       title   标题
     * @apiParam {String}       description   简介
     * @apiParam {Int}          sort   排序
     * @apiParam {Int}          tags   便签(多个','分割)
     * @apiParam {Int}          is_top   是否置顶
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function addVideo()
    {
        $info = input('param.');
        $id = DB::name('Video')->insertGetId($info);
        $res = model('Video')->setTagCount($id, $info['tags']);
        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /article/Video/verifyTitle 验证视频标题
     * @apiVersion              1.0.0
     * @apiName                 verifyTitle
     * @apiGROUP                Video
     * @apiDescription          验证视频标题
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          category_id   分类ID
     * @apiParam {String}       title   标题
     * @apiParam {Int}          id   视频ID(可选) (编辑时候传)
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function verifyTitle()
    {
        $category_id = input('param.category_id', '', 'intval');
        $title = input('param.title', '', 'htmlspecialchars');
        $id = input('param.id', '', 'intval');
        $where['title'] = $title;
        $where['category_id'] = $category_id;
        if(!empty($id)) {
            $where['id'] = ['neq', $id];
        }
        $info = DB::name('Video')->where($where)->find();
        if ($info) {
            $this->response('-1', '标题已存在');
        } else {
            $this->response('1', '标题可以新增');
        }

    }
    /**
     * @api {post} /article/Video/editVideo 编辑查看视频
     * @apiVersion              1.0.0
     * @apiName                 editVideo
     * @apiGROUP                Video
     * @apiDescription          编辑查看视频
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id   文章ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *       category_id   文章分类ID
     *       college_type  类型ID(1 大学 2 专业 3职业)
     *       college_id   关联职业专业或者院校的视频ID
     *       catrgory_top_id  上一级ID
     *       term_type   终端类型
     *       cover   封面，列表页图片url地址   
     *       play_type   视频源来源地址,1:本地上传,2:iframe网页来源,3:外链视频源地址
     *       content   如果是play_type是1的话，就是admin_upload的id，不是就是网页或者视频源地址
     *       title   标题
     *       description   简介
     *       sort   排序
     *       tags   便签(多个','分割)
     *       is_top   是否置顶
     * ]
     * }
     *
     */
    public function editVideo()
    {
        $id = input('param.id');
        $info = model('Video')->getVideoInfo($id);
        $c_info = model('Category')->getCategoryInfo($info['category_id']);
        $info['category_top_id'] = (string)$c_info['catrgory_top_id'];
        if(isset($info['college_type'])) {
            if($info['college_type'] == 1) {
                $college_api = config('college_api');
                $admin_key = config('admin_key');
                $url =  $college_api.'/index/CollegeAdmin/getCollegeInfoById';
                $param['college_id'] = $info['college_id'];
                $param['admin_key'] = $admin_key;
                $data = curl_api($url, $param, 'post');
                $info['college_name'] = $data['data']['title'];
            }elseif($info['college_type'] == 2){
                $college_api = config('college_api');
                $admin_key = config('admin_key');
                $url =  $college_api.'/index/Major/getLevelMajorV2';
                $param['type_id'] = $info['college_id'];
                $param['admin_key'] = $admin_key;
                $data = curl_api($url, $param, 'post');
                $info['major_number'] = $data['data']['major_number'];
                $info['major_type_number'] = $data['data']['major_type_number'];
                $info['major_top_number'] = $data['data']['major_top_number'];
                $info['type_id'] = $data['data']['type_id'];
            }elseif($info['college_type'] == 3){
                $college_api = config('college_api');
                $admin_key = config('admin_key');
                $url =  $college_api.'/index/Occupation/getLevelOccupation';
                $param['occupation_id'] = $info['college_id'];
                $param['admin_key'] = $admin_key;
                $data = curl_api($url, $param, 'post');
                $info['occupation_id'] = $data['data']['occupation_id'];
                $info['type_id'] = $data['data']['type_id'];
            }
        }
        $info['college_id'] = intval($info['college_id']);
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据');
        }
    }


    /**
     * @api {post} /article/Video/saveVideo 提交修改视频
     * @apiVersion              1.0.0
     * @apiName                 saveVideo
     * @apiGROUP                Video
     * @apiDescription          提交修改视频
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          category_id   文章分类ID
     * @apiParam {Int}          college_type  类型ID(1 大学 2 专业 3职业)
     * @apiParam {Int}          college_id  关联职业专业或者院校的视频ID
     * @apiParam {Int}          term_type   终端类型
     * @apiParam {String}       cover   封面，列表页图片url地址   
     * @apiParam {Int}          play_type   视频源来源地址,1:本地上传,2:iframe网页来源,3:外链视频源地址
     * @apiParam {String}       content   如果是play_type是1的话，就是admin_upload的id，不是就是网页或者视频源地址
     * @apiParam {String}       title   标题
     * @apiParam {String}       description   简介
     * @apiParam {Int}          sort   排序
     * @apiParam {Int}          tags   便签(多个','分割)
     * @apiParam {Int}          is_top   是否置顶
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function saveVideo()
    {
        $info = input('param.');
        DB::name('Video')->update($info);
        $s_info = DB::name('Video')->where(['id'=>$info['id']])->find();
        if($s_info['status'] == 1 && $s_info['term_type'] == 1) {
            model('common')->sendPushToR40('','UPDATE_TERM_MENU');
        }
        $res = model('Video')->setTagCount($info['id'], $info['tags']);

        if ($res !==false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /article/Video/deleteVideo 设置视频状态
     * @apiVersion              1.0.0
     * @apiName                 deleteVideo
     * @apiGROUP                Video
     * @apiDescription          设置视频状态
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id   视频ID
     * @apiParam {Int}          status   状态（1：正常，0：禁用）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function deleteVideo()
    {
        $id = input("param.id");
        $status = input("param.status", '1','intval');
        if (empty($id)) {
            $this->response('-1', '数据不能为空');
        }
         //推送一体机
        $info = DB::name('Video')
            ->where('id', 'in', $id)
            ->where(['term_type'=>1])
            ->find();
        if($info) {
            model('common')->sendPushToR40('','UPDATE_TERM_MENU');
        }
        $res = DB::name('Video')
            ->where('id', 'in', $id)
            ->update(['status'=>$status]);
        if ($res !==false) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }
}
