<?php

namespace app\api\controller\lessonplan;

use think\Db;
use app\common\controller\Api;
use app\lessonplan\model\Menu;

class MenuManage extends Api
{
    /**
     * @api {post} /api/Menu/teachMenu 学校端购买套餐目录
     * @apiVersion 1.0.0
     * @apiName teachMenu
     * @apiGroup MenuApi
     * @apiDescription 学校端购买套餐目录
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} school_id 学校id .
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function teachMenu()
    {
        $where['school_id'] = input('param.school_id');
        $id = Db::name('teaching_sale')->where($where)->value('combo_id');
        if (empty($id)) {
            $this->response(-1, '学校未购买套餐');
        } else {
            $chapter = Db::name('teaching_combo')->where("id='$id'")->value('chapter_arr');
            $menuModel = new Menu();
            $str = trim($chapter, ',');
            $chapter = explode(',', $str);
            $list = $menuModel->getDetail($chapter);
            $this->response(1, '获取成功', $list);
        }

    }
}