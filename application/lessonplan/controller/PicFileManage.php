<?php
namespace app\lessonplan\controller;

use think\Db;
use app\common\controller\Base;
use app\common\controller\Admin;
use app\lessonplan\model\PicFileManages;
use app\lessonplan\model\Video;

class PicFileManage extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @api {get|post} /lessonplan/PicFileManage/picFileList 图片/文件列表
     * @apiVersion 1.0.0
     * @apiName picFileList
     * @apiGroup PicFileManage
     * @apiDescription 图片/文件列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} file_name 搜索名字（子目录名字）.
     * @apiParam {Int} page 页号,默认1.
     * @apiParam {Int} pagesize 页大小,默认10.
     * @apiParam {String} file_type 文件类型，1:图片,2:文档,3:视频.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     *  "list": [
     *{
     *"id": 2,
     * "uid": 0,
     * "url": "",
     * "source_type": 1,
     *  "file_name": "",
     * .....
    }
     * }
     */
    public function picFileList()
    {
        $param=input('param.');
        $where['file_type']=$param['file_type'];
        $where['source_type ']=1;
        if ($where['file_type']!=1&&$where['file_type']!=2) {
            $this->response(-1, '参数错误');
        }
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
        if ($param['file_name']!="") {
            if (strlen($param['file_name'])>=0) {
                $where['file_name']=['like','%'.trim($param['file_name']).'%'];
            }
        }
        $limit=$this->getLimit($pageId, $pageSize);
        $picFileLists['total']=model('PicFileManages')->getPicFileLisTotal($where);
        $picFileLists['page_num']=ceil($picFileLists['total']/$pageSize);
        $picsFilesList=model('PicFileManages')->getPicFileList($where, $limit);
        $arrs = array(
            'pageSize' =>  (int)$pageSize,
            'total' => $picFileLists['total'],
            'list' => $picsFilesList
        );
        $this->response(1, '获取成功', $arrs);
    }

    /**
     * @api {get|post} /lessonplan/PicFileManage/uploadPicFile 图片/文件上传
     * @apiVersion 1.0.0
     * @apiName uploadPicFile
     * @apiGroup PicFileManage
     * @apiDescription 图片/文件列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "添加成功",
    }
     * }
     */
    public function uploadPicFile(){
        $param=input('param.');
        if (!empty($param)) {
            for ($i=0; $i<count($param); $i++) {
                $param[$i]['name'] = htmlspecialchars_decode($param[$i]['name']);
                $upload['ext'] = substr(strrchr($param[$i]['name'],'.'),1);
                $upload['file_name'] = substr($param[$i]['name'],0,strrpos($param[$i]['name'], '.'));
                $upload['url'] = $param[$i]['url'];
                $upload['file_size'] = $param[$i]['size'];
                $upload['source_type'] = 1;
                if(!empty($param[$i]['file_type'])){
                    $upload['file_type'] = $param[$i]['file_type'];
                    if($upload['file_type']=='1'){
                        $upload['thumb'] = $param[$i]['url'];
                    }
                    if($upload['ext']=='doc'|| $upload['ext']=='docx'||$upload['ext']=='ppt'||$upload['ext']=='pptx'||$upload['ext']=='xlsx'||$upload['ext']=='xls'){
                        $upload['covert_status'] = '1';
                    }
                    Db::name('admin_upload')->insert($upload);
                }
            }
            $this->response(1, '添加成功');
        }
    }

    /**
     * @api {get|post} /lessonplan/PicFileManage/deletePicFile 图片/文件删除
     * @apiVersion 1.0.0
     * @apiName deletePicFile
     * @apiGroup PicFileManage
     * @apiDescription 图片/文件删除
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id 图片/文件ID.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "删除成功",
    }
     * }
     */
    public function deletePicFile(){
        if (request()->isPost()) {
            $param = input('param.');
            if (!empty($param)) {
                $data = $param['id'];
                $picFile = model('Video')->delupload($data);
                if ($picFile == '1') {
                    $this->response(1, '删除成功');
                } else {
                    $this->response(-1, '该资源已被使用，无法删除');
                }
            }
        }
    }

    /**
     * @api {get|post} /lessonplan/PicFileManage/delAllPicFile 图片/文件批量删除
     * @apiVersion 1.0.0
     * @apiName delAllPicFile
     * @apiGroup PicFileManage
     * @apiDescription 图片/文件批量删除
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id 图片/文件ID数组.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "删除成功",
    }
     * }
     */
    public function delAllPicFile(){
        if (request()->isPost()) {
            $param = input('param.');
            if (!empty($param)) {
                $id = $param['id'];
                for ($i=0; $i<count($id); $i++ ) {
                    $data=$id[$i];
                    model('Video')->delupload($data);
                }
                $this->response(1, '删除成功');
            }
        }
    }

    public function getLimit($page, $size)
    {
        $start=($page-1)*$size;
        $limit=$start.','.$size;
        return $limit;
    }
}

