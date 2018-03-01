<?php

namespace app\business\controller;

use think\Db;
use \app\business\model\Business;
use think\Request;
use app\common\controller\Admin;

class BusinessManage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /business/BusinessManage/getList 查看招商列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                BusinessManage
     * @apiDescription          查看招商列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {String}       orderType 排序类型（0：添加时间，1：渠道来源 ，2：合作类型，3：处理状态）可选，默认 0
     * @apiParam {String}       sort 排序（0：逆序，1：顺序）可选，默认 0
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          page_num 总页数
     *                          page 页码<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     * "count": 3,
     * "page_num": 1,
     * "page": "1",
     * "pagesize": 20,
     * "list": [
     * {
     * "id": 3,
     * "number": "230939",//招商编号
     * "company_name": "福州大道之行科技有限公司",//公司名称
     * "source_type": 1,//商机来源渠道，1:高中升学一体机,2:校园在线APP,3:校园在线官网
     * "type": 1,//合作形式，1:内容咨询合作
     * "post_man": "",//联系人
     * "phone": "123343455",//联系人电话
     * "email": "123343455@qq.com",//联系人邮箱
     * "create_time": "2017-08-29 16:06:19",//create_time
     * "update_time": "2017-08-29 16:06:19",//update_time
     * "status": 0,//回复处理的状态，0:未处理,1:已处理
     * "uid": 0,//处理人的当前用户ID
     * "source_type_name": "高中升学一体机",//渠道来源
     * "type_name": "内容咨询合作",//合作类型
     * "status_name": "未处理",//处理状态
     * "user_name": ""//处理人工号
     * },
     * ]
     * }
     * }*
     */
    public function getList()
    {
        $keyword = input('keyword', '', 'htmlspecialchars');
        $orderType = input('orderType', 0, 'int');
        $sort = input('sort', 0, 'int');
        $pagesize = input('pagesize', 20, 'int');

        //排序数组
        $orderTypeArr = array(0 => 'id', 1 => 'source_type', 2 => 'type', 3 => 'status');
        $sortType = array(0 => 'DESC', 1 => 'ASC');
        $where = '';
        $order = 'create_time DESC';
        $field = '*';
        if (isset($_REQUEST['keyword'])) {
            //判断是否属于来源搜索
            $sourceType = model('Business')->getSourceIdByName($keyword);
            $typeType = model('Business')->getTypeIdByName($keyword);
            if (!empty($sourceType)) {
                $where['source_type'] = $sourceType;
            } else {
                if (!empty($typeType)) {
                    $where['type'] = $typeType;
                } else {
                    $where['company_name|content'] = array('like', array('%' . $keyword . '%'));
                }
            }
        }
        if (!empty($orderTypeArr[$orderType]) && !empty($sortType[$sort])) {
            $order = $orderTypeArr[$orderType] . ' ' . $sortType[$sort];
        }
        $list = model('Business')->getBusinessList($where, $order, $field, $pagesize);

        if ($list['count'] > 0) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', array('count' => 0));
        }
    }

    /**
     * @api {post} /business/BusinessManage/getInfo 查看招商详情
     * @apiVersion              1.0.0
     * @apiName                 getInfo
     * @apiGROUP                BusinessManage
     * @apiDescription          查看招商详情
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}       id 招商记录ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     * "id": 3,
     * "number": "230939",//招商编号
     * "company_name": "福州大道之行科技有限公司",//公司名称
     * "source_type": 1,//商机来源渠道，1:高中升学一体机,2:校园在线APP,3:校园在线官网
     * "type": 1,//合作形式，1:内容咨询合作
     * "post_man": "",//联系人
     * "phone": "123343455",//联系人电话
     * "email": "123343455@qq.com",//联系人邮箱
     * "content":"",//合作内容
     * "create_time": "2017-08-29 16:06:19",//create_time
     * "update_time": "2017-08-29 16:06:19",//update_time
     * "status": 0,//回复处理的状态，0:未处理,1:已处理
     * "uid": 0,//处理人的当前用户ID
     * "source_type_name": "高中升学一体机",//渠道来源
     * "type_name": "内容咨询合作",//合作类型
     * "status_name": "未处理",//处理状态
     * "user_name": ""//处理人工号
     * }
     * }*
     */
    public function getInfo()
    {
        $id = input('id');
        if (empty($id)) {
            $this->response('-1', 'id不能为空');
        }
        $info = model('Business')->getInfo($id);
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据');
        }
    }

    /**
     * @api {post} /business/BusinessManage/updateStatus 修改回复状态
     * @apiVersion              1.0.0
     * @apiName                 updateStatus
     * @apiGROUP                BusinessManage
     * @apiDescription          修改回复状态
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 招商记录ID
     * @apiParam {Int}          status 状态 （1 已处理 0 未处理）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function updateStatus()
    {
        $id = input('param.id');
        $status = input('param.status','0', 'intval');
        if (empty($id)) {
            $this->response('-1', 'id不能为空');
        }
        $data['status'] = $status;
        $data['uid'] = $this->uid;
        $info = Db::name('Business')->where(['id'=>$id])->update($data);
        if ($info !== false) {
            $this->response('1', '修改成功', $info);
        } else {
            $this->response('-1', '修改失败');
        }
    }

    /**
     * @api {post} /business/BusinessManage/exportExcel 批量导出excel文件
     * @apiVersion              1.0.0
     * @apiName                 exportExcel
     * @apiGROUP                BusinessManage
     * @apiDescription          批量导出excel文件
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id  id列表，多个id用","分隔 按id排序顺序导出
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function exportExcel()
    {
        $id = input('id');
        if (empty($id)) {
            $this->response('-1', 'id不能为空');
        }

        $expTitle = date('YmdHis');
        $expCellName = array(
            array('number', '合作编号'),
            array('source_type_name', '渠道来源'),
            array('create_time', '提交时间'),
            array('company_name', '公司名称'),
            array('company_add', '公司地址'),
            array('post_man', '联系人'),
            array('phone', '联系电话'),
            array('status_name', '处理状态'),
            array('user_name', '处理人')
        );//原始数组
        $where['id'] = array('in', $id);
        $list = model('Business')->getBusinessList($where, "field(id,$id)", '*', 9999);
        if (empty($list['list'])) {
            $this->response('-1', '请选择导出数据');
        }

        $expTableData = $list['list'];
        exportExcel($expTitle, $expCellName, $expTableData);
    }
}
