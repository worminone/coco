<?php
/*
 * 内容--轮播图对外中国校园在线官网接口
 * */
namespace app\api\controller\zgxyzx;

use app\common\controller\Base;
use app\article\model\SlideShow;

class Slide extends Base
{
    /**
     * @api {get} /api/zgxyzx.slide/getList 获取轮播图列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                zgxyzx
     * @apiDescription          获取轮播图列表数据，开发者：高榕
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
     *      title: 我是轮播图标题,
     *      image_url: 图片url地址,
     *      jump_obj:  跳转对象，1:文章,2:大学推荐,3:专业推荐,4:普通专题,5:大学,6:专业,7:职业,8:网页,
     *      obj_value: 跳转对象的值,
     *      create_time: 创建时间
     * }
     * ]
     * }
     */
    public function getList()
    {
        $slideShow = new SlideShow();
        $where = [];
        $where['term_type'] = 3;

        //分类
        $categoryId = input('get.category_id', 0);
        if ($categoryId) {
            $where['category_id'] = $categoryId;
        }

        $limit = input('get.count_limit', config('pagesize'), 'intval');
        $userList = $slideShow->apiList($where, $limit);
        //         aa($userInfo);
        $this->response('1', '获取成功', $userList);
    }
}
