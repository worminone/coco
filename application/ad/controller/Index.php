<?php
namespace app\ad\controller;

use app\common\controller\Admin;
use think\Request;
use think\Cache;
use app\ad\model\Ad;
use app\article\model\Common;


class Index extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {get} /ad/index/getList 广告列表（高榕）
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGroup                Ad
     * @apiDescription          所有广告列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          release_status 根据时间投放状态（可选）,1：未投放，2：正在投放，3：投放结束,
     * @apiParam {Number}       money 广告金额（可选）
     * @apiParam {Int}          show_type 展示方式（可选）,1:轮播图,2:文章
     * @apiParam {Int}          status 状态（可选）：1:上架，0：下架
     * @apiParam {String}       start_date 状态（可选），投放开始日期
     * @apiParam {String}       end_date 状态（可选），投放结束日期
     * @apiParam {String}       province 投放省份ID（可选），全国为0，多个省份ID用逗号隔开
     * @apiParam {String}       keyword 查询标题关键字（可选）
     * @apiParam {Int}          pagesize 每页的条目数（可选），默认为10
     * @apiParam {Int}          page 页码（可选），默认为1
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     * @apiSuccessExample  {json} Success-Response:
     * {
            "code": "1",
            "msg": "获取成功",
            "data": {
                "count": 1,
                "pagesize": 10,
                "data": [
                    {
                        "id": 1,
                        "advertiser_id": 广告主ID,
                        "show_type": 1,
                        "post_id": 2,
                        "start_date": "2017-09-26",
                        "end_date": "2017-10-07",
                        "money": "111.00",
                        "release_status": 1,
                        "status": 1,
                        "remark": 备注,
                        "show_count": 展示次数,
                        "clicke_count": 点击次数,
                        "have_ad_tag": 是否添加水印,
                        "create_time": "2017-09-26 09:34:08",
                        "advertiser_name": "北京大学",
                        "region_name": "天津,上海,北京",
                        "content": {
                            "id": 2,
                            "uid": 2,
                            "category_id": 4,
                            "title": "阿斯顿发斯蒂芬",
                            "cover": "http://image.zgxyzx.net/FlSv_5Iex2J0R6rEaf3Kv3wOAbzD",  //缩略图
                            "term_type_title": '高中升学一体机',  //1:高中升学一体机,2:校园在线APP,3:校园在线官网
                            "status": 0,
                            "sort": 0,
                            "image_url": "http://image.zgxyzx.net/Fp70yRr1rW3wClgG5VkbCg72TJmA",
                            "jump_obj": 1,
                            "obj_value": "242",
                            "create_time": "2017-08-26 16:10:52",
                            "update_time": "2017-08-29 21:07:44",
                            "ad_id": 0,
                            "province": "2",
                            "city": "0",
                            "jump_obj_title": "",
                            "term_name": "校园在线APP",
                            "category_name": "社会新闻",
                            "province_name": "天津"
                        }
                    }
                ]
            }
        }
     */
    public function getList()
    {
        $where = [];
        $status = input('get.status', 1, 'intval');
        if (key_exists('status', input('get.'))) {
            $where['status'] = $status;
        }

        $release= input('get.release_status', 0, 'intval');
        $release && $where['release_status'] = $release;

        $showType = input('get.show_type', 0, 'intval');
        $showType && $where['show_type'] = $showType;

        $start_date = input('get.start_date', '');
        $end_date = input('get.end_date', '');
        if ($start_date && $end_date) {
            $where['start_date'] = ['between', ["$start_date","$end_date"]];
            $where['end_date'] = ['between', ["$start_date","$end_date"]];
        } elseif (!$start_date && !$end_date) {

        } else {
            $this->response(-1, '投放开始日期和投放结束日期不能都为空');
        }

        $province = input('get.province', '');
        if ($province) {
            $province = explode(',', $province);
            $where['province'] = ['in', $province];
        }

        $key = input('get.keyword', '');
        if (key_exists('keyword', input('get.'))) {
            $where['title'] = ['like', "%".$key."%"];
        }

        $page = input('get.page', 1, 'intval');
        $pageSize = input('get.pagesize', config('pagesize'), 'intval');
        $model = new Ad();

        $list = $model->getList($where, $page, $pageSize);
        $data = ['count'=>$list['count'],'pagesize'=>config('pagesize'), 'data'=>$list['data']];

        $this->response('1', '获取成功', $data);
    }


    //外网调试用的方法
    public function apiList()
    {
        $where = [];
        $status = input('get.status', 1, 'intval');
        if (key_exists('status', input('get.'))) {
            $where['status'] = $status;
        }

        $release= input('get.release_status', 0, 'intval');
        if ($release) {
            $where['release_status'] = $release;
        }

        $showType= input('get.show_type', 0, 'intval');
        if ($release) {
            $where['show_type'] = $showType;
        }

        $start_date = input('get.start_date', '');
        $end_date = input('get.end_date', '');
        if ($start_date && $end_date) {
            $where['start_date'] = ['between', ["$start_date","$end_date"]];
            $where['end_date'] = ['between', ["$start_date","$end_date"]];
        } elseif (!$start_date && !$end_date) {

        } else {
            $this->response(-1, '投放开始日期和投放结束日期不能都为空');
        }

        $model = new Ad();
        $list = $model->apiList(9);

        $this->response('1', '获取成功', $list);
    }

    /**
     * @api {post} /ad/index/adAdd    添加一条广告数据（高榕）
     * @apiVersion              1.0.0
     * @apiName                 adAdd
     * @apiGroup                Ad
     * @apiDescription          添加轮播图广告
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          show_type 广告的展示方式，1：轮播图广告，2：文章广告
     * @apiParam {Int}          advertiser_id 广告主ID，这里广告主是高校，也就是高校ID
     * @apiParam {Int}          post_id 轮播图的ID或者公共文章库的ID
     * @apiParam {String}       start_date 广告投放的开始日期
     * @apiParam {String}       end_date 广告投放的结束日期
     * @apiParam {Int}          province 投放的省份，0是全国
     * @apiParam {Int}          have_ad_tag 是否添加广告水印，1-添加，0-不添加
     * @apiParam {String}       remark 备注
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function adAdd()
    {
        $post = input('post.');
        if (! key_exists('province', $post)) {
            $this->response(-1, '投放的区域没有选择');
        }

        $provinceArr = explode(',', $post['province']);
        if (count($provinceArr) == 34) {
            $post['province'] = '0';
        }

        $this->_checkData($post);

        $post['user_id'] = $this->uid;
        $ad = new Ad();
        $bool = $ad->addAd($post);
        if ($bool !== false) {
            $this->response(1, '添加成功');
        } else {
            $this->response(-1, '添加失败');
        }
    }


    /**
     * @api {post} /ad/index/adUpdate    更新一条广告数据（高榕）
     * @apiVersion              1.0.0
     * @apiName                 adUpdate
     * @apiGroup                Ad
     * @apiDescription          更新一条广告数据
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 广告ID
     * @apiParam {Int}          show_type 广告的展示方式，1：轮播图广告，2：文章广告
     * @apiParam {Int}          advertiser_id 广告主ID，这里广告主是高校，也就是高校ID
     * @apiParam {Int}          post_id 轮播图的ID或者公共文章库的ID
     * @apiParam {String}       start_date 广告投放的开始日期
     * @apiParam {String}       end_date 广告投放的结束日期
     * @apiParam {Int}          province 投放的省份，0是全国
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function adUpdate()
    {
        $post = input('post.');
        if (! $post['id']) {
            $this->response(-1, '广告的ID不能为空');
        }

        if (! key_exists('province', $post)) {
            $this->response(-1, '投放的区域没有选择');
        }

        $provinceArr = explode(',', $post['province']);
        if (count($provinceArr) == 34) {
            $post['province'] = '0';
        }


        $this->_checkData($post);

        $ad = new Ad();
        $bool = $ad->addAd($post);
        if ($bool !== false) {
            $this->response(1, '修改成功');
        } else {
            $this->response(-1, '修改失败');
        }
    }


    /**
     * @api {get} /ad/index/view    获取一条广告数据（高榕）
     * @apiVersion              1.0.0
     * @apiName                 view
     * @apiGroup                Ad
     * @apiDescription          获取一条广告数据
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 广告ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function view()
    {
        $id = input('get.id');
        if (! $id) {
            $this->response(-1, '广告的ID不能为空');
        }

        $data = model('Ad')->getList(['a.id'=>$id]);
        if ($data) {
            $this->response(1, '获取成功', $data);
        } else {
            $this->response(-1, '获取失败');
        }
    }

    //添加和修改广告数据的时候检测广告的数据
    private function _checkData($data)
    {
        if (! $data['show_type']) {
            $this->response(-1, '广告的展示方式不能为空');
        }

        if (! $data['advertiser_id']) {
            $this->response(-1, '投放的高校不能为空');
        }

        if (! $data['post_id']) {
            $this->response(-1, '轮播图的ID或者公共文章库的ID不能为空');
        }

        if (! $data['start_date']) {
            $this->response(-1, '广告投放的开始日期不能为空');
        }

        if (! $data['end_date']) {
            $this->response(-1, '广告投放的结束日期不能为空');
        }

        if ($data['start_date'] > $data['end_date']) {
            $this->response(-1, '广告投放的开始日期不能晚于结束日期');
        }

        if (! key_exists('province', $data) ) {
            $this->response(-1, '广告投放的区域不能为空');
        }

        $ad = new Ad();

        if ($ad->checkValid($data) == false) {
            $this->response(-1, '这个时间段该区域已经安排了广告投放');
        }
    }

    /**
     * @api {post} /ad/index/setStatus 更改广告状态（高榕）
     * @apiVersion              1.0.0
     * @apiName                 setStatus
     * @apiGroup                Ad
     * @apiDescription          广告上架和下架
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 轮播图ID
     * @apiParam {Int}          status 轮播图状态,1:上架,0:下架

     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function setStatus()
    {
        $post = input('post.');
        $id = $post['id'];
        if (! $id) {
            $this->response(-1, '广告ID不能为空');
        }

        if (! key_exists('status', $post)) {
            $this->response(-1, '广告状态不能为空');
        }


        $idsArr = explode(',', $id);
        //广告上架检查排期的冲突
        if ($post['status'] == 1) {
            foreach ($idsArr as $one) {
                $adInfo = model('Ad')->getList(['a.id'=>$one]);
                $adProvince = db('ad_province')->where('ad_id='.$one)->column('province');
                $adInfo['province'] = implode(',', $adProvince);
//                 aa($adInfo);
                $isValid = model('Ad')->checkValid($adInfo);
                if ($isValid == false) {
                    $this->response(-1, '广告ID为 ' . $one . ' 排期冲突，无法上架');
                }
            }
        }

        $where = [];
        $where['id'] = ['in', $idsArr];


        $bool = db('ad')->where($where)->update(['status'=>$post['status']]);
        if ($bool !== false) {
            //触发推送
            $pushModel = new Common();
            $pushModel->sendPushToR40('','update_banner');

            $this->response(1, '更新成功');
        } else {
            $this->response(-1, '更新失败');
        }
    }

    /**
     * @api {get} /ad/index/fromTime 广告时间排期(高榕）
     * @apiVersion              1.0.0
     * @apiName                 fromTime
     * @apiGroup                Ad
     * @apiDescription          广告时间排期
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       start_date 开始日期
     * @apiParam {String}       end_date   结束日期
     * @apiParam {Int}          show_type 广告的展示方式，1：轮播图广告，2：文章广告

     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */

    public function fromTime()
    {
        $data = input('get.');
        if (! $data['show_type']) {
            $this->response(-1, '广告的展示方式不能为空');
        }

        if (! $data['start_date']) {
            $this->response(-1, '广告投放的开始日期不能为空');
        }

        if (! $data['end_date']) {
            $this->response(-1, '广告投放的结束日期不能为空');
        }

        if ($data['start_date'] > $data['end_date']) {
            $this->response(-1, '广告投放的开始日期不能晚于结束日期');
        }

//         if (! key_exists('province', $data) ) {
//             $this->response(-1, '广告投放的区域不能为空');
//         }

        $data = model('Ad')->fromTime($data);

        if ($data) {
            $this->response(1, '获取成功', $data);
        } else {
            $this->response(-1, '获取失败');
        }
    }


    /**
     * @api {get} /ad/index/fromRegion 广告地区排期(高榕）
     * @apiVersion              1.0.0
     * @apiName                 fromRegion
     * @apiGroup                Ad
     * @apiDescription          广告地区排期
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       this_month 当前年月份，例如：2017-10
     * @apiParam {Int}          province 省份ID
     * @apiParam {Int}          show_type 广告的展示方式，1：轮播图广告，2：文章广告

     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     *
     * @apiSuccessExample  {json} Success-Response:
     * {
          "code": 1,
          "msg": "获取成功",
          "data":  [
                {
                    "date": "01",
                    "count": -1    //今天是2017-11-02日，2017-11-01已过，-1代表无法排期，应该为灰色
                },
                {
                    "date": "02",
                    "count": 0    //0代表该天排期已满，无法排期
                },
                {
                    "date": "03",
                    "count": 2    //2代表该天该地区已经排了2个广告
                }
          ]
        }

     */
    public function fromRegion()
    {
        $data = input('get.');
        if (! $data['show_type']) {
            $this->response(-1, '广告的展示方式不能为空');
        }

        if (! $data['this_month']) {
            $this->response(-1, '广告投放的开月份不能为空');
        } else {
            $thisMonth = $data['this_month'];
            $data['start_date'] = $thisMonth . '-01';

            $BeginDate = $data['start_date'];
            $data['end_date'] = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
        }


        if (! key_exists('province', $data) ) {
            $this->response(-1, '广告投放的区域不能为空');
        }

        $data = model('Ad')->fromRegion($data);

        if ($data) {
            $this->response(1, '获取成功', $data);
        } else {
            $this->response(-1, '获取失败');
        }
    }

    /**
     * @api {get} /ad/index/statistics 广告数据统计(高榕）
     * @apiVersion              1.0.0
     * @apiName                 statistics
     * @apiGroup                Ad
     * @apiDescription          广告数据统计
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          ad_id 广告ID
     * @apiParam {String}       start_date 开始日期
     * @apiParam {String}       end_date   结束日期
     * @apiParam {Int}          show_type  广告的展示方式（可选），1：轮播图广告，2：文章广告
     * @apiParam {Int}          term_type 终端类型（可选）,1:高中一体机,2:校园在线APP,3:校园在线官网,4:高中学生web
     * @apiParam {Int}          download 是否下载报表（可选）， 1-下载报表
     * @apiParam {Int}          province 省份ID（可选）

         * @apiSuccess {Int}    code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function statistics()
    {
        $get = input('get.');
        $where = [];


        $id = input('get.ad_id', 0, 'intval');
        if (! $id) {
            $this->response(-1, '广告ID不能为空');
        }
        $where['ad_id'] = $id;

        if (! $get['start_date'] || ! $get['end_date']) {
            $this->response(-1, '统计数据的开始时间和结束时间不能为空');
        }

        $startDate = $get['start_date'];
        $startTimeStamp = strtotime($startDate);
        $endDate = $get['end_date'];
        $endTimeStamp = strtotime($endDate);
        $where['the_date'] = ['between', [$startDate, $endDate]];

        if (key_exists('show_type', $get)) {
            $where['show_type'] = $get['show_type'];
        }

        if ($get['term_type']) {
            $where['term_type'] = $get['term_type'];
        }

        if (key_exists('province', $get) && $get['province'] !== '') {
            $where['province_id'] = intval($get['province']);
        }

//         aa($where);
        $data = db('ad')->field('id, title')->find($id);

//         $cacheKey = md5(serialize($where));
        $cacheKey = uniqid();
        $statistics = Cache::get($cacheKey);
        if (! $statistics) {
            $field = "the_date, SUM(view_count) AS view_count, SUM(click_count) AS click_count";
            $statistics = db('ad_statistics')->field($field)->group('the_date')->where($where)->select();
            Cache::set($cacheKey, $statistics, 3600);
        }




        //两者天数相等就不需要继续下面的操作
        if (count($statistics) !== diffBetweenTwoDays($startDate, $endDate)) {

            $dateListData = [];
            foreach ($statistics as $one) {
                $timeKey = strtotime($one['the_date']);
                $dateListData[$timeKey] = $one;
            }

            $timeList = range($startTimeStamp,$endTimeStamp,3600 * 24);

            foreach ($timeList as $time) {

                if(! key_exists($time, $dateListData)) {
                    $theDate = date('Y-m-d', $time);
                    $dateListData[$time] = ['the_date'=>"$theDate", 'view_count'=>0, 'click_count'=>0];
                }
            }
            //按照时间戳正向排序
            sort($dateListData);

            $data['statistics'] = array_values($dateListData);
        } else {
            $data['statistics'] = $statistics;
        }


        // 下载报表
        if (key_exists('download', $get) && $get['download'] == 1) {

            $expTitle = date('YmdHis');
            $expCellName = array(
                array('the_date', '日期'),
                array('view_count', '曝光量'),
                array('click_count', '点击量')
            );//原始数组
            if (empty($data['statistics'])) {
                $this->response('-1', '没有任何数据');
            }

            $expTableData = $data['statistics'];

            exportExcel($expTitle, $expCellName, $expTableData);
        } else {
            if ($data) {
                $this->response(1, '获取成功', $data);
            } else {
                $this->response(-1, '获取失败');
            }

        }

    }

}