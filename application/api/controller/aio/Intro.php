<?php
/*
 * 内容--轮播图对外高中一体机接口，展示的轮播图包括轮播图运营内容和广告轮播图
 * */
namespace app\api\controller\aio;

use app\common\controller\Api;

class Intro extends Api
{
    /**
     * @api {get} /api/aio.Intro/aboutUs 关于我们
     * @apiVersion              1.0.0
     * @apiName                 aboutUs
     * @apiGROUP                AIO
     * @apiDescription          关于我们
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function aboutUs()
    {
        $data = Db::name('about_us')->find();
        $this->response(1, '获取成功', $data);
    }
    /**
     * @api {get} /api/aio.Intro/contactUs 关于我们
     * @apiVersion              1.0.0
     * @apiName                 aboutUs
     * @apiGROUP                AIO
     * @apiDescription          关于我们
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function contactUs()
    {
        $data = Db::name('contact_us')->find();
        $this->response(1, '获取成功', $data);
    }

}
