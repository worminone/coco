<?php

namespace app\api\controller\business;

use think\Db;
use \app\message\model\Message;
use think\Request;
use app\common\controller\Api;

class BusinessManage extends Api
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /api/business.BusinessManage/addInfo 添加招商信息
     * @apiVersion              1.0.0
     * @apiName                 addInfo
     * @apiGROUP                BusinessManage
     * @apiDescription          添加招商信息
     * @apiParam {String}       company_name  公司名称
     * @apiParam {String}       company_add  公司地址
     * @apiParam {Int}          source_type   商机来源渠道
     * @apiParam {Int}          type   合作形式
     * @apiParam {String}       post_man   联系人
     * @apiParam {String}       phone  联系人电话
     * @apiParam {String}       email 联系人邮箱
     * @apiParam {String}       content  合作内容
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function addInfo()
    {
        $info = input('param.');
        $company_name = input('company_name', '');
        $content = input('content', '');
        if (empty($info)) {
            $this->response('-1', '提交信息不能为空');
        }
        $res = DB::name('Business')->insertGetId($info);
        if ($res) {
            $where['id'] = $res;
            $where['number'] = date('Ymd') . $res;
            DB::name('Business')->update($where);
            //添加消息
            $this->addMessage(3, $res, $company_name, $content);
            $this->response('1', '你的信息已提交', $res);
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /message/index/addMessage   添加消息
     * @apiVersion              1.0.0
     * @apiName                 addMessage
     * @apiGroup                MESSAGE
     * @apiDescription          添加消息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          type   消息类型,1:院校入驻审核,2:高中入驻审核,3:招商加盟,4:大学信息审核,
     * @apiParam {Int}          post_id 消息对应数据对象跳转主键
     * @apiParam {String}       title 消息标题
     * @apiParam {String}       content 消息内容
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 数据详情
     */
    public function addMessage($type, $post_id, $title, $content)
    {

        $messageModel = new Message();
        return $messageModel->addInfo($type, $post_id, $title, $content);

    }

    /**
     * @api {post} /api/business.BusinessManage/addInfoV2 添加招商信息
     * @apiVersion              1.0.0
     * @apiName                 addInfo
     * @apiGROUP                BusinessManage
     * @apiDescription          添加招商信息
     * @apiParam {String}       company_name  公司名称
     * @apiParam {String}       company_add  公司地址
     * @apiParam {Int}          source_type   商机来源渠道
     * @apiParam {Int}          type   合作形式
     * @apiParam {String}       post_man   联系人
     * @apiParam {String}       phone  联系人电话
     * @apiParam {String}       email 联系人邮箱
     * @apiParam {String}       content  合作内容
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function addInfoV2()
    {
        $info = input('param.');
        $post_man = input('post_man', '');
        $content = input('content', '');
        if (empty($info)) {
            $this->ajaxReturn('-1', '提交信息不能为空', '', 'jsonp');
        }
        $res = DB::name('Business')->insertGetId($info);
        if ($res) {
            $where['id'] = $res;
            $where['number'] = date('Ymd') . $res;
            DB::name('Business')->update($where);
            //添加消息
            $this->addMessage(3, $res, $post_man, $content);
            $this->ajaxReturn('1', '你的信息已提交', $res, 'jsonp');
        } else {
            $this->ajaxReturn('-1', '信息提交失败', '', 'jsonp');
        }
    }
}
