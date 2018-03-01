<?php
namespace app\index\controller;

use app\article\model\Common;

class Cron extends \think\Controller
{
    public function __construct()
    {
        parent::__construct();
    }



    //广告投放时间到了结束时间自动下架，广告到了开始投放时间自动更改投放状态
    public function checkAd()
    {

        $today = date('Y-m-d');

        //投放开始
        $where = "status=1 AND start_date <= '$today' AND end_date >= '$today'";
        $data = [];
        $data['release_status'] = 2;
//         $list = db('ad')->where($where)->select();
//         aa($list);

        $updateCount1 = db('ad')->where($where)->update($data);
        unset($data);

        //投放结束
        $where = "end_date < '$today'";
        $data = [];
        $data['release_status'] = 3;
        $data['status'] = 0;
        $updateCount2 = db('ad')->where($where)->update($data);


        //修改为未投放
        $where = "status=1 AND start_date > '$today'";
        $updateCount3 = db('ad')->where($where)->update(['release_status'=>1]);

        if ($updateCount1 > 0 || $updateCount2 >0 || $updateCount3 >0) {
            //触发推送
            $pushModel = new Common();
            $pushModel->sendPushToR40('','update_banner');
        }

        echo '操作成功';

    }
}