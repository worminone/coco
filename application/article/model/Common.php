<?php

namespace app\article\model;

use think\Db;

class Common
{
    /**
     * Created by PhpStorm.
     * User: levey-pc
     * Date: 2017/9/21
     * Time: 13:26
     */


    /***
     * 定义更新类型常量
     */
    // define("UPDATE_BANNER","UPDATE_BANNER");  //是否更新轮播图
    // define("UPDATE_CENTER_MENU","UPDATE_CENTER_MENU");  //是否更新主菜单
    // define("UPDATE_SCHOOL_INFO","UPDATE_SCHOOL_INFO");  //是否更新顶部学校信息(更新账号密码也在此通道)


    /***
     * 推送类型,按需添加
     * @param $school_id
     * @param $update_fun
     *
     * how to use
     *
     * require_once("r40_push.php");
     * sendPushToR40(10086,UPDATE_CENTER_MENU);
     *
     */

    public function sendPushToR40($school_id, $update_fun){
        $update_fun = strtolower($update_fun);
        $in_data =  array(
            'school_id' => $school_id,  //当前登录账号的school_id(有学校账号关联则推送该参数,无关联则为 0 )
            'update_time' => intval(time()),  //当前更新时间 (timestap) 无需更改
            $update_fun => true,
        );
        $this->sendPushBase($in_data);
    }

    /***
     *  推送方法体,请勿更改
     *  @param $data
     */
    public function sendPushBase($data){
        if( strpos($_SERVER['HTTP_HOST'], 'zgxyzx.net') ) {
            $host = 'net.zgxyzx.aio.UPDATE_STATUS_R40';
        } else {
            $host = 'net.zgxyzx.aio.UPDATE_STATUS_R40_TEST_VERSION';
        }
        $post_data = array(
            'data' => array(
                'action' => $host,   //推送动作,切勿更改
                'name' => 'aio',
                'aio_data' => $data
            )
        );
        $headers = array(
            'X-LC-Id: uWi9CtXBbeQ72BrphSJVtafn-gzGzoHsz',
            'X-LC-Key: VnMX7FrHEtYHdlk0bKDljSB2',
            'Content-Type: application/json'
        );
        $url = 'https://leancloud.cn/1.1/push';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        $result = curl_exec($ch);
        curl_close($ch);
        // return $result; //返回值输出 (无用,可不输出)  成功返回为后面所示,其余为失败返回  {"objectId":"4OsSJD56zyFmRUrb","created
    }

}
