<?php
namespace app\api\controller\gw;

use think\Db;
use app\common\controller\Api;
use app\article\model\SlideShow;
use app\message\model\Message;

class GwIndex extends Api
{
    /**
     * @api {post} /api/gw.GwIndex/getSlide 获取轮播图(gw)
     * @apiVersion 1.0.0
     * @apiName getSlide
     * @apiGroup gw
     * @apiDescription 获取轮播图
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} page 当前页数.
     * @apiParam {string} pagesize 分页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id:   轮播图id ,
     *      image_url: 图片地址
     * }
     * ]
     * }
     *
     */
    public function getSlide()
    {
        $slideShow = new SlideShow();
        $where = [];
        $status = input('param.status', 1, 'intval');
        $termType = input('param.term_type', 3, 'intval');
        $page = input('param.page', 1, 'intval');
        $pagesize = input('param.pagesize', 3, 'intval');
        $categoryId = input('get.category_id', 0);
        if ($status > 0) {
            $where['status'] = $status;
        }
        //终端类型
        if ($termType) {
            $where['term_type'] = $termType;
        }
        $categoryId = config('gw_config.slide_category');
        //分类
        if ($categoryId) {
            $where['category_id'] = $categoryId;
        }
        $data = $this->getPageList('SlideShow', $where, 'id desc', '*', $pagesize);
//        $data = $slideShow->getList($where, $page, $pagesize);
        $this->ajaxReturn('1', '获取成功', $data, 'jsonp');
    }

    /**
     * @api {post} /api/gw.GwIndex/getCustomerCaseList 获取客户案例列表
     * @apiVersion              1.0.0
     * @apiName                 getCustomerCaseList
     * @apiGROUP                gw
     * @apiDescription          获取客户案例列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {int}          page   当前页
     * @apiParam {int}          pagesize 当前页数
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 客户案例ID,
     *   school_name: 学校名称,
     *   school_motto: 校训,
     *   school_pic: 学校图片,
     * }
     */
    public function getCustomerCaseList()
    {
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        $list = $this->getPageList('CustomerCase', $where, 'sort desc, id desc', '*', $pagesize);
        if ($list) {
            $this->ajaxReturn('1', '获取成功', $list, 'jsonp');
        } else {
            $this->ajaxReturn('-1', '暂无数据', '', 'jsonp');
        }
    }


    /**
     * @api {post} /api/gw.GwIndex/getPartnerList 获取合作伙伴列表
     * @apiVersion              1.0.0
     * @apiName                 getPartnerList
     * @apiGROUP                gw
     * @apiDescription          获取合作伙伴列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {int}          page   当前页
     * @apiParam {int}          pagesize 当前页数
     *
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 合作伙伴ID,
     *   title: 合作伙伴名称,
     *   website: 网址,
     * }
     */
    public function getPartnerList()
    {
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        $list = $this->getPageList('Partner', $where, 'sort desc, id desc', '*', $pagesize);
        if ($list) {
            $this->ajaxReturn('1', '获取成功', $list, 'jsonp');
        } else {
            $this->ajaxReturn('-1', '暂无数据', '', 'jsonp');
        }
    }

    /**
     * @api {post} /api/gw.GwIndex/getBrandImageList 获取品牌形象列表
     * @apiVersion              1.0.0
     * @apiName                 getBrandImageList
     * @apiGROUP                gw
     * @apiDescription          获取品牌形象列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {int}          page   当前页
     * @apiParam {int}          pagesize 当前页数
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 品牌形象ID,
     *   describe: 文字说明,
     *   img: 缩略图,
     * }
     */
    public function getBrandImageList()
    {
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        $list = $this->getPageList('BrandImage', $where, 'sort desc, id desc', '*', $pagesize);
        if ($list) {
            $this->ajaxReturn('1', '获取成功', $list, 'jsonp');
        } else {
            $this->ajaxReturn('-1', '暂无数据', '', 'jsonp');
        }
    }


    /**
     * @api {post} /api/gw.GwIndex/addFeedbackInfo 添加问题反馈
     * @apiVersion              1.0.0
     * @apiName                 addFeedbackInfo
     * @apiGROUP                gw
     * @apiDescription          添加问题反馈
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {Int}          type    问题类型,
     * @apiParam {String}       telphone 联系方式,
     * @apiParam {String}       contact  联系人,
     * @apiParam {String}       describe 问题描述,
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function addFeedbackInfo()
    {
        $param = input('param.');
        $param['term_type'] = 3;
        $res = DB::name('Feedback')->insertGetId($param);
        if ($res) {
            $messageModel = new Message();
            $messageModel->addInfo('6', $res, $param['contact'], $param['telphone']);
            $this->ajaxReturn('1', '你的信息已提交', '', 'jsonp');
        } else {
            $this->ajaxReturn('-1', '信息提交失败', '', 'jsonp');
        }
    }

    /**
     * @api {post} /api/gw.GwIndex/addSchoolEntryInfo 申请入驻信息
     * @apiVersion              1.0.0
     * @apiName                 addSchoolEntryInfo
     * @apiGROUP                gw
     * @apiDescription          申请入驻信息
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiParam {String}       contact  联系人
     * @apiParam {String}       telphone 联系手机
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function addSchoolEntryInfo()
    {
        $info = input('param.');
        $info['number'] = time().rand(10,99);
        $info['term_type'] = 3;
        if (empty($info)) {
            $this->response('-1', '参数不能为空');
        }
        $res = DB::name('SchoolEntry')->insertGetId($info);
        if ($res) {
            $messageModel = new Message();
            $messageModel->addInfo('5', $res, $info['contact'], $info['telphone']);
            $this->ajaxReturn('1', '你的信息已提交', '', 'jsonp');
        } else {
            $this->ajaxReturn('-1', '信息提交失败', '', 'jsonp');
        }
    }

    /**
     * @api {post} /api/gw.GwIndex/getFeedbackType 获取问题类型
     * @apiVersion              1.0.0
     * @apiName                 getFeedbackType
     * @apiGROUP                gw
     * @apiDescription          获取问题类型
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    问题反馈ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function getFeedbackType(){
        $config = new Message();
        $info = $config->getFeedback();
        $this->ajaxReturn('1', '获取成功', $info, 'jsonp');
    }

    /**
     * @api {post} /api/gw.GwIndex/getExRegion 已入驻的省市
     * @apiVersion 1.0.0
     * @apiName getExRegion
     * @apiGroup getExRegion
     * @apiDescription 已入驻的省市
     *
     * @apiParam {String} token 用户的token.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "操作成功"
     *  data: [
     *      {
     *      region_id: 省份ID,
     *      region_name: "省份名称",
     *      city: [
     *      {
     *          region_id: 城市ID,
     *          region_name: "城市名称"
     *      }
     *      ]
     *   },
     * }
     */
    public function getExRegion()
    {
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getExRegion';
        $param['status'] = 0;
        $data = curl_api($url, $param, 'post');
        $this->ajaxReturn('1', '获取成功', $data['data'], 'jsonp');
    }

    /**
     * @api {post} /api/gw.GwIndex/getSchoolList 获取学校名称
     * @apiVersion 1.0.0
     * @apiName getSchoolList
     * @apiGroup SchoolPicture
     * @apiDescription 获取学校名称
     *
     * @apiParam {String} token      用户的token.
     * @apiParam {Int}    city_id    区ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "操作成功"
     *  data: [{
     *      id：学校ID
     *      sch_name: 学校名称,
     *      province_id: "省份ID"
     *      city_id :城市ID
     *   },
     * }
     */
    public function getSchoolList()
    {
        $city_id = input('param.city_id');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getSchoolList';
        $param['city_id'] = $city_id;
        $data = curl_api($url, $param, 'post');
        $this->ajaxReturn('1', '获取成功', $data['data'], 'jsonp');
    }

    /**
     * @api {post} /api/gw.GwIndex/getSchoolPicture 查看编辑中学登录界面
     * @apiVersion              1.0.0
     * @apiName                 getSchoolPicture
     * @apiGROUP                gw
     * @apiDescription          查看编辑中学登录界面
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id    中学登录界面ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id:            中学登录界面ID,
     *   school_badge:  校徽,
     *   school_name    学校名称
     *   school_img     轮播图图片(多个‘,’分割)
     *   school_id      学校ID
     * }
     *
     */
    public function getSchoolPicture()
    {
        $param['id']= input('param.id', '', 'int');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getSchoolPic';
        $data = curl_api($url, $param, 'post');
        $this->ajaxReturn('1', '获取成功', $data['data'], 'jsonp');
    }

    /**
     * @api {post} /api/gw.GwIndex/getSystemInfo 查看校园在线官网配置
     * @apiVersion              1.0.0
     * @apiName                 getSystemInfo
     * @apiGROUP                gw
     * @apiDescription          查看招商列表
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiParam {String}       title   网站标题，一句话介绍概括
     * @apiParam {String}       keyword   网站搜索引擎关键词
     * @apiParam {String}       description   网站搜索引擎描述
     * @apiParam {String}       logo   网站LOGO,299*95
     * @apiParam {String}       img404   404图片,500*300
     * @apiParam {String}       copyright   版权信息
     * @apiParam {String}       icp_num   网站备案号
     * @apiParam {String}       statistics_code   站长统计
     * @apiParam {String}       tel   公司电话
     * @apiParam {String}       xyzx_service_wechat   校园在线微信服务号
     * @apiParam {String}       download_qr   IOS和安卓下载二维码
     * @apiParam {String}       content_mail 内容投稿
     * @apiParam {String}       ad_mail 广告合作
     * @apiParam {String}       business_mail 招商邮箱
     * @apiParam {String}       business_phone  招商电话
     * @apiParam {String}       user_mail 用户支持
     */
    public function getSystemInfo()
    {
        $field = 'title,keyword,description,logo,img404,copyright,icp_num,company_name,xyzx_service_wechat,
        download_qr,content_mail,ad_mail,business_mail,business_phone,user_mail';
        $info = DB::name('XyzxConfig')->field($field)->find();
        if ($info) {
            $this->ajaxReturn('1', '获取成功', $info, 'jsonp');
        } else {
            $this->ajaxReturn('-1', '暂无数据', '', 'jsonp');
        }
    }

}

