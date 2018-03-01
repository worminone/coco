<?php
namespace app\data\controller;

use think\Db;
use app\common\controller\Base;

class Intro extends Base
{
    /**
     * @api {post} /data/Intro/getIntroVideoList 获取介绍视频列表
     * @apiVersion 1.0.0
     * @apiName getIntroVideoList
     * @apiGroup Video
     * @apiDescription 获取介绍视频列表（黄铃杰）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} type_id 类型ID(1.在线课堂引导 2.生涯测评引导,3.我的素材引导)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getIntroVideoList()
    {
        $type_id = input('param.type_id', 1);
        $config  = config('teacher_config');
        $where['term_type'] = $config['term_type'];
        if($type_id ==1){
            $where['category_id'] = $config['online'];
        } elseif ($type_id ==2) {
            $where['category_id'] = $config['evaluate'];
        } elseif ($type_id ==3) {
            $where['category_id'] = $config['mySource'];
        }
        $where['status'] = 1;
        $list = Db::name('video')->where($where)->select();
        foreach ($list as &$v) {
            $v['cover'] = $v['cover'].'?imageView2/1/w/680/h/383';
        }
        $this->response(1, '查询成功', $list);

    }
}