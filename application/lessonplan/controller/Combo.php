<?php
namespace app\lessonplan\controller;

use think\Db;
use app\common\controller\Base;
use app\common\controller\Admin;
use think\Cache;

class Combo extends Base
{
    /**
     * @api {post} /lessonplan/Combo/getComboList 获取套餐列表
     * @apiVersion 1.0.0
     * @apiName getComboList
     * @apiGroup Combo
     * @apiDescription 获取套餐列表
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
    public function getComboList()
    {
        $where = array();
        $pageSize = input('param.pageSize', 10);
        $name = trim(input('param.name', ''));
        $page = input('param.page', 1);
        if (strlen($name)>0) {
            $where['description'] = ['like', '%'.$name.'%'];
        }
        if ($pageSize == 0) {
            $pageSize = 10;
        }
        $limit = $this->getLimit($page, $pageSize);
        $data['total'] = model('Combo')->getComboCount($where);
        $data['pageSize'] =  intval($pageSize);
        $data['list'] = model('Combo')->getComboData($where, $limit);
        $this->response(1, '获取成功', $data);
    }

    /**
 * @api {post} /lessonplan/Combo/getShowComboList 获取可见的套餐列表
 * @apiVersion 1.0.0
 * @apiName getShowComboList
 * @apiGroup Combo
 * @apiDescription 获取可见的套餐列表（黄铃杰）
 *
 * @apiParam {String} page 页码.
 * @apiParam {String} pagesize 每页条数.
 *
 * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
 * @apiSuccess {String} msg 成功的信息和失败的具体信息.
 */
    public function getShowComboList()
    {
        $pagesize = input('param.pagesize', 10);
        $page = input('param.page', 1);
        if ($pagesize == 0) {
            $pagesize = 10;
        }
        $data['list'] = [['id'=>0,'description'=>'全部']];
        $where['is_show'] = 1;
        $limit = $this->getLimit($page, $pagesize);
        $data['total'] = model('Combo')->getComboCount($where);
        $data['pagesize'] =  intval($pagesize);
        $list = model('Combo')->getComboData($where, $limit);
        $data['list'] = array_merge($data['list'],$list);
        $this->response(1, '获取成功', $data);
    }
    /**
     * @api {post} /lessonplan/Combo/getCatalogueList 获取一级目录列表
     * @apiVersion 1.0.0
     * @apiName getCatalogueList
     * @apiGroup Combo
     * @apiDescription 获取一级目录列表（黄铃杰）
     *
     * @apiParam {String} id 套餐ID (不限为0)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getCatalogueList()
    {
        $tmp = [['id'=>0,'name'=>'全部']];
        $id = input('param.id',0);
        $list = model('Combo')->getCatalogueList($id);
        $tmp = array_merge($tmp,$list);
        $this->response(1, '获取成功', $tmp);
    }
    /**
     * @api {post} /lessonplan/Combo/getComboDetail 获取套餐细节（查看）
     * @apiVersion 1.0.0
     * @apiName getComboDetail
     * @apiGroup Combo
     * @apiDescription 获取套餐细节
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id  套餐ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getComboDetail()
    {
        $where['id'] = input('param.id');
        if (empty($where['id'])) {
            $this->response(-1, '参数错误');
        }
        $data = Db::name('teaching_combo')->where($where)->value('chapter_arr');
        $data = trim($data,',');
        $ids = explode(',',$data);
        $list = model('Combo')->getDetail($ids);
        if (empty($list)){
            $list=array();
        }
        $this->response(1, '获取成功', $list);
    }
    /**
     * @api {post} /lessonplan/Combo/getComboSelectDetail 获取套餐目录细节
     * @apiVersion 1.0.0
     * @apiName getComboSelectDetail
     * @apiGroup Combo
     * @apiDescription 获取套餐目录细节（黄铃杰）
     *
     * @apiParam {String} school_id 班级ID
     * @apiParam {String} id  套餐ID.(不限为0)
     * @apiParam {String} first_catalogue 一级目录ID(不限为0)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getComboSelectDetail()
    {

        $school_id = input('param.school_id', 0);
        $id = input('param.id', 0);
        $first_catalogue = input('param.first_catalogue', 0);
        $content = model('Combo')->getAllDetail($school_id,$id,$first_catalogue);
        $this->response(1, '获取成功', $content);
    }
    /**
     * @api {post} /lessonplan/Combo/getComboData 获取套餐数据（修改）
     * @apiVersion 1.0.0
     * @apiName getComboData
     * @apiGroup Combo
     * @apiDescription 获取套餐数据
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id 套餐ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getComboData()
    {
        $where['id'] = input('param.id');
        if (empty($where['id'])) {
            $this->response(-1, '参数错误');
        }
        $name = input('param.name');
        $data = Db::name('teaching_combo')->where($where)->value('chapter_arr');
        $data = trim($data,',');
        $ids = explode(',',$data);
        $list['id'] = $where['id'];
        $list['name'] = model('Combo')->getComboName($where);
        $is_show = Db::name('teaching_combo')->where($where)->value('is_show');
        if ($is_show==0) {
            $list['is_show'] = false;
        } else {
            $list['is_show'] = true;
        }
        $list['arr'] = $data;
        $this->response(1, '获取成功', $list);
    }
    /**
     * @api {post} /lessonplan/Combo/delComboData 删除套餐数据
     * @apiVersion 1.0.0
     * @apiName delComboData
     * @apiGroup Combo
     * @apiDescription 删除套餐数据
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id 套餐ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function delComboData()
    {
        $where['combo_id'] = ['in', input('param.id')];
        $flag = model('Combo')->delComboData($where);
        if ($flag == 1) {
            $this->response(1, '删除成功');
        } else {
            $this->response(-1, '套餐已在销售中，无法删除！');
        }
    }
    /**
     * @api {post} /lessonplan/Combo/updateComboData 新增/修改套餐数据
     * @apiVersion 1.0.0
     * @apiName updateComboData
     * @apiGroup Combo
     * @apiDescription 删除套餐数据
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} description 套餐描述.
     * @apiParam {String} ids 选中的章节ID（以,分割）.
     * @apiParam {String} id 套餐ID.
     * @apiParam {String} is_show 是否显示(true.显示，false.隐藏)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function updateComboData()
    {
        $tmp=array();
        $count = 0;
        $where['id'] = input('param.id', 0);
        $ids=explode(',', input('param.ids'));
        $is_show = input('param.is_show', 'false');
        if($is_show == 'false'){
            $is_show=0;
        } else {
            $is_show=1;
        }
        foreach ($ids as $k=>$val) {
            $pid=Db::name('teaching_catalogue')->where("id='$val'")->value('pid');
            if ($pid > 0) {
                $tmp[$count++] = $val;
            }
        }
        $ids=','.implode(',',$tmp).',';
        //$ids=','.input('param.ids').',';
        $description = input('param.description', '', 'htmlspecialchars_decode');
        $save=array(
            'description' => $description,
            'chapter_arr' => $ids,
            'is_show' =>$is_show
        );
        if ($where['id']>0) {
            Db::name('teaching_combo')->where($where)->update($save);
        } else {
            Db::name('teaching_combo')->insert($save);
        }
        $this->response(1, '保存成功');
    }

    public function getLimit($page, $size)
    {
        $start = ($page-1)*$size;
        $limit = $start.','.$size;
        return $limit;
    }
}