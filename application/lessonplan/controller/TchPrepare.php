<?php
namespace app\lessonplan\controller;

use app\common\controller\Base;
use app\common\controller\Admin;
use think\Db;
use app\lessonplan\model\TchPrepares;


class TchPrepare extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @api {post} /lessonplan/TchPrepare/prepareList 教学备课管理列表
     * @apiVersion 1.0.0
     * @apiName prepareList
     * @apiGroup TchPrepare
     * @apiDescription 教学备课管理列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} name 搜索名字（子目录名字）.
     * @apiParam {Int} page 页号,默认1.
     * @apiParam {Int} pagesize 页大小,默认10.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "成功",
     * "data": {
     * "pageSize": ,
     * "total": 总数,
     * "list": [
     * {
     * "id": “子目录ID” ,
     * "name": 1,
     * "description": "作业描述",
     * "cover": "http：//12312312312312312312",//封面
     * "prepareId: ": "备课ID（点击编辑时候的GET请求的ID的ID）"
     * ]
     * }
     * }
     */
    public function prepareList()
    {
        $param=input('param.');
        $where['pid']=['<>','0'];
        if (!empty($param['pageSize'])) {
            if ($param['pageSize']>0) {
                $pageSize=$param['pageSize'];
            } else {
                $this->response(-1, '参数有误');
            }
        } else {
            $pageSize=10;
        }
        if (!empty($param['page'])) {
            if ($param['page']>0) {
                $pageId=$param['page'];
            } else {
                $this->response(-1, '参数有误');
            }
        } else {
            $pageId=1;
        }
        if (!empty($param['name'])) {
            if (strlen($param['name'])>0) {
                $where['name']=['like','%'.trim($param['name']).'%'];
            }
        }
        $limit=$this->getLimit($pageId, $pageSize);
        $preList['total']=model('TchPrepares')->getPreLisTotal($where);
        $preList['page_num']=ceil($preList['total']/$pageSize);
        $preparesList=model('TchPrepares')->getPreLis($where, $limit);
        foreach ($preparesList as $key => $arr) {
           $id = $preparesList[$key]['id'];
           $prepareLesson = model('TchPrepares')->getLesson($id);
           $preparesList[$key]['description'] = $prepareLesson['description'];
           $preparesList[$key]['cover'] = $prepareLesson['cover'];
           $preparesList[$key]['prepareId'] = $prepareLesson['id'];
        }
        $arrs = array(
            'pageSize' =>  (int)$pageSize,
            'total' => $preList['total'],
            'list' => $preparesList
        );
        $this->response(1, '获取成功', $arrs);
    }

    /**
     * @api {get|post} /lessonplan/TchPrepare/prepareMain 教学资源管理详情
     * @apiVersion 1.0.0
     * @apiName prepareMain
     * @apiGroup TchPrepare
     * @apiDescription 教学资源管理详情
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} id 二级菜单ID.
     * @apiParam {String} show_id 显示ID（1.HTML）.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "成功",
     * "data": {
     * "pageSize": ,
     * "total": 总数,
     * "data": [
     * {
     * "id": “备课ID” ,
     * "description": "作业描述",
     * "cover": "http：//12312312312312312312",//封面
     * "second_level_name: ": "二级菜单名字"
     * "first_level_name: ": "一级菜单名字"
     * file: “选取文件列表”[
     *  file_name: 文件名
     *  file_type: 文件类型，1:图片,2:文档,3:视频
     *  url: 文件地址
     *  thumb: 封面图
     * ]
     * ]
     * }
     * }
     */
    public function prepareMain()
    {
        $param['id']=input('param.id', 0);
        $show_id=input('param.show_id', 0);
        $where['catalogue_id']=$param['id'];
        $preMain = Db::name('prepare_lesson')->where($where)->find();
        $catalogue_second = Db::name('teaching_catalogue')->where('id',$preMain['catalogue_id'])->find();
        $catalogue_first = Db::name('teaching_catalogue')->where('id',$catalogue_second['pid'])->find();
        $preMain['second_level_name'] = $catalogue_second['name'];
        $preMain['first_level_name'] = $catalogue_first['name'];
        if ($preMain['cover']=='') {
            $preMain['cover'] = 'http://image.zgxyzx.net/default.png';
        }
        $admin_upload = Db::name('admin_upload')->where('source_type','1')->order('file_type','ASC')->select();
        for ($i=0; $i<count($admin_upload); $i++ ) {
            $courseware['prepare_id'] = $preMain['id'];
            $courseware['courseware_id'] =$admin_upload[$i]['id'];
            $admin_upload[$i]['file_type'] =  intval($admin_upload[$i]['file_type']);
            $result = Db::name('prepare_courseware')->where($courseware)->find();
            if (empty($result)) {
                $admin_upload[$i]['hidden'] = true;
            } else {
                $admin_upload[$i]['hidden'] = false;
            }
        }
        $preMain['file']=$admin_upload;
        if ($show_id>0) {
            $preMain['description'] = str_replace(array("\r\n", "\r", "\n"), "<br>", $preMain['description']);
        }
        $this->response(1, '获取成功', $preMain);
    }
    /**
     * @api {get|post} /lessonplan/TchPrepare/prepareDetail 教学资源管理明细（教师端）
     * @apiVersion 1.0.0
     * @apiName prepareDetail
     * @apiGroup TchPrepare
     * @apiDescription 教学资源管理明细
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} id 二级菜单ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function prepareDetail()
    {
        $param = input('param.', 0);
        $where['catalogue_id'] = $param['id'];
        $tmp['prepare_id'] = Db::name('prepare_lesson')->where($where)->value('id');
        $data = Db::name('prepare_courseware')->where($tmp)->column('courseware_id');
        $search['id'] = ['in', $data];
        $list = Db::name('admin_upload')->where($search)->select();
        for ($i=0; $i<count($list); $i++){
            if($list[$i]['ext']=='doc'||$list[$i]['ext']=='docx'){
                $list[$i]['thumb'] ='http://image.zgxyzx.net/word.png';
            }else if ($list[$i]['ext']=='ppt'||$list[$i]['ext']=='pptx'){
                $list[$i]['thumb'] ='http://image.zgxyzx.net/ppt.png';
            }else if ($list[$i]['ext']=='xlsx'||$list[$i]['ext']=='xls'){
                $list[$i]['thumb'] ='http://image.zgxyzx.net/exl.png';
            }else if($list[$i]['ext']=='pdf'){
                $list[$i]['thumb'] ='http://image.zgxyzx.net/pdf.png';
            }
            if($list[$i]['file_type']==3){
                $list[$i]['video_type']='kejian';
            }
        }
        $this->response(1, '获取成功', $list);
    }

    /**
     * @api {post} /lessonplan/TchPrepare/editPrepare 教学资源管理详情更新
     * @apiVersion 1.0.0
     * @apiName editPrepare
     * @apiGroup TchPrepare
     * @apiDescription 教学资源管理详情更新
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} id 备课ID.
     * @apiParam {String} cover 封面地址.
     * @apiParam {String} description 描述.
     * @apiParam {String} admin_upload 选中的课件数组
     * ."admin_upload": [
     *{
     *"id": 2,
     *"uid": 0,
     *"url": "",
     *"source_type": 1,
     *"file_name": "",
     *"file_type": 1,
     *"thumb": "http::",
     *"ext": "",
     *"description": "",
     *},
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "成功",
     * }
     */
    public function editPrepare()
    {
        $param=input('param.');
        if (!empty($param)) {
            $where['id'] = $param['data']['id'];
            $upLesson['cover'] = $param['data']['cover'];
            $upLesson['description'] = $param['data']['description'];
            if(!empty($param['admin_upload'])){
                Db::name('prepare_courseware')->where('prepare_id', $where['id'])->delete();
                $admin_upload = $param['admin_upload'];
                $admin_upload  = array_values($admin_upload);
                for($i=0; $i<count($admin_upload); $i++){
                    $courseware['courseware_id'] =  $admin_upload[$i];
                    $courseware['prepare_id'] =  $where['id'];
                    Db::name('prepare_courseware')->insert($courseware);
                }
            } else {
                Db::name('prepare_courseware')->where('prepare_id', $where['id'])->delete();
            }
            Db::name('prepare_lesson')->where($where)->update($upLesson);
            $this->response(1, '更新成功');
        }
    }

    /**
     * @api {post} /lessonplan/TchPrepare/delPrepare 教学资源管理课件删除
     * @apiVersion 1.0.0
     * @apiName delPrepare
     * @apiGroup TchPrepare
     * @apiDescription 教学资源管理课件删除
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} id 备课ID.
     * @apiParam {String} courseware_id 课件ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "删除成功",
     * }
     */
    public function delPrepare()
    {
        $param=input('param.');
        $where['courseware_id']=$param['courseware_id'];
        $where['prepare_id']=$param['id'];
        Db::name('admin_upload')->where($where)->delete();
        $this->response(1, '删除成功');
    }

    public function getLimit($page, $size)
    {
        $start=($page-1)*$size;
        $limit=$start.','.$size;
        return $limit;
    }
}

