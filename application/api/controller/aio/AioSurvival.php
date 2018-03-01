<?php
namespace app\api\controller\aio;

use think\Db;
use app\common\controller\Api;

class AioSurvival extends Api
{
    /**
     * @api {post} /api/aio.AioSurvival/setAioSurvial 保存一体机存活数据
     * @apiVersion 1.0.0
     * @apiName setAioSurvial
     * @apiGroup AIO
     * @apiDescription 保存一体机存活数据（郑陈菲）
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} token 用户的token
     * @apiParam {int} aio_time_type 刷新类型(1:启动 2:刷新)
     * @apiParam {string} aio_mac_id MAC地址
     * @apiParam {string} aio_rom_version 固件版本
     * @apiParam {string} aio_app_name 软件名称(可为空)
     * @apiParam {string} aio_app_version 软件版本(可为空)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function setAioSurvial()
    {
        $type_arr = [0, 1, 2];
        $type = input('param.aio_time_type', 0);
        $mac = strtoupper(input('param.aio_mac_id', ''));
        $rom_version = input('param.aio_rom_version', '');
        if (empty($mac) || empty($rom_version)) {
            $this->response(-1, 'MAC地址/固件版本不能为空！');
        }
        if (!in_array($type, $type_arr)) {
            $this->response(-1, '刷新类型不正确！');
        }
        $app_name = input('param.aio_app_name', '');
        $app_version = input('param.aio_app_version', '');
        if ($type == 0) {
            $data['shutdown_yn'] = 1;
            $data['startup_time'] = NULL;
            $data['refresh_time'] = NULL;
            $data['first_refresh_time'] = NULL;
        } else {
            $data['shutdown_yn'] = 0;
            $data['rom_version'] = $rom_version;
            $data['app_name'] = $app_name;
            $data['app_version'] = $app_version;

            if ($type == 1) {
                $data['startup_time'] = $data['refresh_time'] = $data['first_refresh_time'] = date("Y-m-d H:i:s");
            }
            if ($type == 2) {
                $data['refresh_time'] = date("Y-m-d H:i:s");
            }
        }
        $data['mac_address'] = $mac;
        $data['updated'] = date("Y-m-d H:i:s");
        $exist = Db::name('aio_survival')->where("mac_address='$mac'")->find();
        if (empty($exist)) {
            if ($type ==1 || $type == 2) {
                if (!empty($data['refresh_time'])) {
                    $data['first_refresh_time'] = $data['refresh_time'];
                } else {
                    $data['first_refresh_time'] = $data['refresh_time'] = $data['startup_time'];
                }
            }
            Db::name('aio_survival')->insert($data);
        } else {
            $data['mac_id'] = $exist['mac_id'];
            Db::name('aio_survival')->update($data);
        }
        $this->response(1, '同步成功');
    }
}

