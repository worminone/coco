<?php

namespace app\lessonplan\controller;

use app\common\controller\Base;
use app\common\controller\Admin;

class QRManage extends Base
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * @api {post} /lessonplan/QRManage/getQR 根据网址得到二维码图片
     * @apiVersion 1.0.0
     * @apiName getQR
     * @apiGroup QRManage
     * @apiDescription 根据网址得到二维码图片
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} url 网址.
     * @apiParam {int} w 图片大小
     * @apiParam {bool} logo 是否带logo
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getQR()
    {
        $url = input('url', '');
        $w = input('w', 4);
        //$logo = input('logo', 'http://' . $_SERVER['SERVER_NAME'] .'/logo.png');
        $logo = 'logo.png';
        $el = input('el', 'h');

        $url = str_replace('.php', '', $url);
        getQRCode($url, $w, $logo, $el);
    }
}