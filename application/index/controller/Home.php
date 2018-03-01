<?php

namespace app\index\controller;

use app\common\controller\Admin;
use app\common\model\Statistics;

class Home extends Admin
{

    /**
     * @api {post} /index/Home/statistics  后台管理首页统计数据查看
     * @apiVersion              1.0.0
     * @apiName                 getInfo
     * @apiGROUP                Index
     * @apiDescription          后台管理首页统计数据查看(高榕)
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
            "code": 1,
            "msg": "获取成功",
            "data": [
                {
                    "name": "高中学校统计情况",
                    "count": 106,            //统计的数量
                    "is_added": 1            //统计的数量的趋势，1是增长，0是持平，-1是下降
                }
            ]
        }
     */
    public function statistics()
    {
        $Statistics = new Statistics();
        $data = [];
        $data[] = $Statistics->highSchoolStatistics();
        $data[] = $Statistics->teacherStatistics();
        $data[] = $Statistics->studentStatistics();
        $data[] = $Statistics->parentStatistics();
        $data[] = $Statistics->collegeStatistics();
        $data[] = $Statistics->highScoolAioStatistics();
        $data[] = $Statistics->productFileStatistics();
        $data[] = $Statistics->uploadFileStatistics();

        $this->response(1, '获取成功', $data);
    }
}
