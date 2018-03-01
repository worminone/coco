<?php
/*
 * 统计接口
 * */
namespace app\api\controller\aio;

use app\common\controller\Api;
use app\common\model\Statistics;

class AioStatistics extends Api
{
    /**
     * @api {post} /api/aio.AioStatistics/adClickStatistics 一体机广告点击统计
     * @apiVersion              1.0.0
     * @apiName                 adClickStatistics
     * @apiGROUP                AIO
     * @apiDescription          一体机广告点击统计（高榕）
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          is_ad  广告ID
     * @apiParam {Int}          province_id  省份ID
     * @apiParam {Int}          show_type  1：轮播图广告，2：文章广告
     * @apiParam {Int}          term_type 终端类型,1:一体机，2：学生端APP，3：官网，4：高中学生WEB
     * @apiParam {Int}          school_id 学校ID（可选）
     *
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 数据详情
     */
    public function adClickStatistics()
    {
        $request = input('request.');
        if (! $request['is_ad']) {
            $this->response(-1, '广告ID不能为空');
        } else {
            $request['ad_id'] = $request['is_ad'];
        }

        if (! $request['province_id']) {
            $this->response(-1, '省份ID不能为空');
        }

        $bool = false;
        if (key_exists('is_ad', $request) && $request['is_ad'] > 0) {
            $model = new Statistics();

            $bool = $model->adClickStatistics($request);

        }

        if ($bool) {
            $this->response(1, '统计数据添加成功');
        } else {
            $this->response(-1, '统计数据添加失败');
        }
    }

}
