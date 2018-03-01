<?php
/*
 * 内容--轮播图对外高中一体机接口，展示的轮播图包括轮播图运营内容和广告轮播图
 * */
namespace app\api\controller\aio;

use app\common\controller\Api;
use app\common\model\Statistics;
use app\article\model\SlideShow;
use app\ad\model\Ad;

class Slide extends Api
{
    /**
     * @api {get} /api/aio.slide/getList 一体机轮播图列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                AIO
     * @apiDescription          获取轮播图列表数据，这里轮播图和轮播图广告会一起整合获取（高榕）
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          province_id  省份ID
     * @apiParam {Int}          school_id 学校ID（可选）
     * @apiParam {Int}          category_id 分类ID（可选）
     * @apiParam {Int}          count_limit 获取数据的数量，默认为10
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 数据详情
     *
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   banner_list:[
     *      title: 我是轮播图标题,
     *      image_url: 图片url地址,
     *      jump_obj:  跳转对象，1:文章,2:普通专题,3:大学推荐,4:专业推荐,5:大学,6:专业,7:职业,8:网页,0:无跳转
     *      obj_value: 跳转对象的值,
     *      create_time: 创建时间,
     *      is_ad:是否是广告,
     *   ],
     *   'slide_time': 时间
     * }
     * ]
     * }
     */
    public function getList()
    {
        //高中一体机
        $termType = 1;
        //最大轮播图数量
        $maxCount = config('max_aio_slide');
        //省份ID
        $provinceId = input('get.province_id', 0);
        if (! $provinceId) {
            $this->response(-1, '一体机所在省份ID不能为空');
        }

        $categoryId = input('get.category_id', 0, 'intval');

        $where = [];
        $where['term_type'] = $termType;
        $where['show_type'] = 1;
        $where['ad_id'] = 0;
        $where['province'] = $provinceId;
        $where['category_id'] = $categoryId;


        $ad = new Ad();

        $adList = $ad->apiList($where);

        $slideShow = new SlideShow();

        $limit = input('get.count_limit', config('pagesize'), 'intval');
        $slideList = $slideShow->apiList($where, $limit);
//         aa($adList);
        $list = array_merge($adList, $slideList);
        $list = array_slice($list, 0, $maxCount);


//         aa($list);
        //添加统计
        foreach ($list as &$one) {
            if (key_exists('is_ad', $one) && $one['is_ad'] > 0) {
                $one['show_type'] = 1;
                $one['ad_id'] = $one['is_ad'];
                $one['province_id'] = $provinceId;
                $one['school_id'] = input('get.school_id', 0);
                $Statistics = new Statistics();
                $Statistics->adShowStatistics($one);
            }
        }

        $data = [
            'banner_list'=>$list,
            'slide_time'=>5
        ];

//         aa($data);
        $this->response('1', '获取成功', $data);
    }

}
