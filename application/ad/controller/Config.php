<?php
namespace app\ad\controller;

use app\common\controller\Admin;
use think\Request;

class Config extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /ad/config/setConfig 配置广告的投放数量
     * @apiVersion              1.0.0
     * @apiName                 setConfig
     * @apiGroup                Ad
     * @apiDescription          配置全国广告和省份广告的投放数量
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          type 广告展示类型，1：轮播图，2：文章
     * @apiParam {Int}          country_count 全国数量
     * @apiParam {Int}          province_count 省份数量数量

     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function setConfig()
    {
        $post = input('post.');
        if (! $post['type']) {
            $this->response(-1, '广告展示类型不能为空');
        }

        if (! $post['country_count']) {
            $this->response(-1, '全国的广告数量设置不能为0');
        }

        if (! $post['province_count']) {
            $this->response(-1, '省份的广告数量设置不能为0');
        }

        $data = $post;
        $data['id'] = $post['type'];
        $data['show_type'] = $post['type'];

        $bool = db('ad_config')->insert($data, true);

        if ($bool !== false) {
            $this->response(1, '更新成功');
        } else {
            $this->response(-1, '更新失败');
        }
    }


    /**
     * @api {get} /ad/config/viewConfig 查看设置广告的投放数量
     * @apiVersion              1.0.0
     * @apiName                 viewConfig
     * @apiGroup                Ad
     * @apiDescription          查看已经配置全国广告和省份广告的投放数量
     * @apiParam {String}       token 已登录账号的token

     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     * {
            "code": 1,
            "msg": "获取成功",
            "data": [
                {
                    "id": 1,
                    "show_type": 1,             //1：轮播图的广告设置
                    "country_count": 22,        //全国投放限制
                    "province_count": 33        //单个省份投放限制
                },
                {
                    "id": 2,
                    "show_type": 2,  //2：文章的广告设置
                    "country_count": 9,
                    "province_count": 99
                }
            ]
        }


     */
    public function viewConfig()
    {
        $data = db('ad_config')->select();

        if ($data) {
            $this->response(1, '获取成功', $data);
        } else {
            $this->response(-1, '尚未设置，请设置');
        }
    }
}