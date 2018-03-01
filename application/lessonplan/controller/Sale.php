<?php
namespace app\lessonplan\controller;

use app\common\controller\Base;
use app\common\controller\Admin;
use think\Db;

class Sale extends Base
{
    /**
     * @api {post} /lessonplan/Sale/getSaleList 获取套餐销售列表
     * @apiVersion 1.0.0
     * @apiName getSaleList
     * @apiGroup Sale
     * @apiDescription 获取套餐销售列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} school_name 名称.
     * @apiParam {String} combo_id 套餐ID.
     * @apiParam {String} page 页码.
     * @apiParam {String} pageSize 每页条数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getSaleList()
    {
        $url = config('school_api').'/api/SchoolData/SchoolIntro';
        $where = array();
        $pageSize = input('param.pageSize', 10);
        $name = trim(input('param.school_name', ''));
        $combo_id = input('param.combo_id', 0);
        $page = input('param.page', 1);
        if ($combo_id>0) {
            $where['combo_id'] = $combo_id;
        }
        if (strlen($name)>0){
            $where['school_name'] = ['like', '%'.$name.'%'];
        }
        if ($pageSize == 0) {
            $pageSize = 10;
        }
        $limit=$this->getLimit($page, $pageSize);
        $data['total'] = model('Sale')->getSaleCount($where);
        $data['pageSize'] = intval($pageSize);
        $data['list'] = model('Sale')->getSaleData($where, $limit);
        foreach ($data['list'] as &$v) {
            $school_id['school_id'] = $v['school_id'];
            $school = curl_api($url, $school_id, 'post');
            $v['school_name'] = $school['data']['school_name'];
        }
        $this->response(1, '获取成功', $data);
    }
    /**
     * @api {post} /lessonplan/Sale/getSaleDetail 获取套餐销售详情
     * @apiVersion 1.0.0
     * @apiName getSaleDetail
     * @apiGroup Sale
     * @apiDescription 获取套餐销售详情
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id 销售套餐ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getSaleDetail()
    {
        $where['id'] = input('param.id', 0);
        $data = model('Sale')->getSaleDetail($where);
        if (!empty($data)) {
            $this->response(1, '获取成功', $data);
        } else {
            $this->response(-1, '无数据');
        }
    }
    /**
     * @api {post} /lessonplan/Sale/addSale 新增套餐销售
     * @apiVersion 1.0.0
     * @apiName addSale
     * @apiGroup Sale
     * @apiDescription 新增套餐销售
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} combo_id 套餐ID.
     * @apiParam {String} school 学校信息(格式：school_id,school_name)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addSale()
    {
        $combo_id = input('param.combo_id');
        $school = explode(',', input('param.school'));
        $save=array(
            'combo_id' => $combo_id,
            'school_id' => $school[0],
            'school_name' => $school[1],
        );
        //判断学校是否购买
        $flag = model('Sale')->haveSale($save['school_id']);
        if ($flag>0) {
            $this->response(-1, '学校已购买其他套餐');
        } else {
            Db::name('teaching_sale')->insert($save);
            $this->response(1, '新增成功');
        }

    }
    /**
     * @api {post} /lessonplan/Sale/updateSale 修改套餐销售
     * @apiVersion 1.0.0
     * @apiName updateSale
     * @apiGroup Sale
     * @apiDescription 修改套餐销售
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id 销售ID.
     * @apiParam {String} combo_id 套餐ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function updateSale()
    {
        $where['id'] = input('param.id');
        $save['combo_id'] = input('param.combo_id');
        //判断学校是否购买
        Db::name('teaching_sale')->where($where)->update($save);
        $this->response(1, '保存成功');
    }
    /**
     * @api {post} /lessonplan/Sale/delSale 删除套餐销售
     * @apiVersion 1.0.0
     * @apiName delSale
     * @apiGroup Sale
     * @apiDescription 删除套餐销售
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} id 销售ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function delSale()
    {
        $where['id'] = ['in', input('param.id')];
        Db::name('teaching_sale')->where($where)->delete();
        $this->response(1, '删除成功');
    }

    public function getLimit($page, $size)
    {
        $start = ($page-1)*$size;
        $limit = $start.','.$size;
        return $limit;
    }
}