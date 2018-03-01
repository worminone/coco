<?php
/**
 * Created by PhpStorm.
 * User: Zhengchenfei
 * Date: 2017/12/25 0025
 * Time: 14:58
 */
namespace app\data\controller;

use app\common\controller\Admin;
use think\Db;

class AioData extends Admin
{
    public static $status_labels = [
        1 => '正常使用',
        2 => '未绑定',
        3 => '报废',
        4 => '维修',
    ];

    public static $use_labels = [
        1 => '体验',
        2 => '销售',
        3 => '租赁',
        4 => '借用',
    ];

    public static $type_labels = [
        1 => '站立',
        2 => '壁挂',
    ];

    public static $normal_status = [1, 4];

    public static $import_column_map = [
        '学校省份' => 'province',
        '学校地区' => 'city',
        '学校区县' => 'county',
        '学校名称' => 'school_id',
        '绑定及状态' => 'status',
        '厂商' => 'manufacturer_id',
        'MAC地址' => 'mac',
        '负责人' => 'in_charge',
        '收件人' => 'address_man',
        '联系电话' => 'phone',
        '类型' => 'use_type',
        '发货地' => 'post_address',
        '发货时间' => 'post_time',
        //'固件型号' => 'firmware',
        '颜色' => 'color',
        '样式' => 'the_type',
        '摆放位置' => 'place',
        '渠道商' => 'canal_id',
        '运输方式' => 'transport',
        '地址' => 'shipping_address',
        '备注' => 'remark',
    ];

    /**
     * @api {post} /data/AioData/getAioSurvival 获取一体机存活资料
     * @apiVersion 1.0.0
     * @apiName getAioSurvival
     * @apiGroup AioData
     * @apiDescription 关机（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} mac 一体机mac地址.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getAioSurvival()
    {
        $mac = strtoupper(input('param.mac', ''));
        if (empty($mac)) {
            $this->response(-1, 'MAC地址不能为空！');
        }
        $rs = Db::name('aio_survival')->where("mac_address='$mac'")->find();
        if (!empty($rs)) {
            $live_msg = caculateSurvival($rs);
            $data['ver_name'] = $rs['app_name'];
            $data['ver_num'] = $rs['app_version'];
            $data['rom_version'] = $rs['rom_version'];
        } else {
            $live_msg = "0小时";
            $data['ver_name'] = '';
            $data['ver_num'] = '';
            $data['rom_version'] = '';
        }
        $data['online'] = $live_msg;
        $this->response(1, '成功', $data);
    }

    /**
     * @api {post} /data/AioData/addAioData 新增一体机数据
     * @apiVersion 1.0.0
     * @apiName addAioData
     * @apiGroup AioData
     * @apiDescription 新增一体机数据（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} province_id 省份ID.
     * @apiParam {int} city_id 城市ID.
     * @apiParam {int} district_id 区域ID.
     * @apiParam {int} school_id 学校ID.
     * @apiParam {int} status 状态（1:正常使用,2:未绑定,3:报废,4:维修）.
     * @apiParam {int} manufact_id 厂商ID.
     * @apiParam {String} mac MAC地址.
     * @apiParam {String} in_charge 负责人.
     * @apiParam {String} address_man 收件人.
     * @apiParam {String} phone 联系电话.
     * @apiParam {int} use_type 类型.
     * @apiParam {String} post_address 发货地.
     * @apiParam {String} post_time 发货时间.
     * @apiParam {String} firmware 固件型号.
     * @apiParam {String} color 颜色.
     * @apiParam {int} the_type 样式.
     * @apiParam {int} canal_id 渠道商ID.
     * @apiParam {String} transport 运输方式.
     * @apiParam {String} shipping_address 收货地址.
     * @apiParam {String} remark 备注.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addAioData()
    {
        $data['province'] = $province_id = input('param.province_id', 0);
        $data['city'] = $city_id = input('param.city_id', 0);
        $data['county'] = $district_id = input('param.district_id', 0);
        $data['school_id'] = $school_id = input('param.school_id', 0);
        $data['status'] = $status = input('param.status', 0);
        $status_arr = self::$normal_status;
        if (!empty($status) && in_array($status, $status_arr)) {
            if (empty($province_id) || empty($city_id) || empty($district_id) || empty($school_id)) {
                $this->response(-1, '学校资料不能为空！');
            }
        }

        $data['manufacturer_id'] = $manufact_id = input('param.manufact_id', 0);
        $data['mac'] = $mac = strtoupper(input('param.mac', ''));
        $data['in_charge'] = $in_charge = input('param.in_charge', '');
        $data['address_man'] = $address_man = input('param.address_man', '');
        $data['phone'] = $phone = input('param.phone', '');
        $data['use_type'] = $use_type = input('param.use_type', 0);
        if (empty($manufact_id) || empty($mac) || empty($in_charge)
            || empty($address_man) || empty($phone) || empty($use_type)
        ) {
            $this->response(-1, '厂商/MAC地址/负责人/收件人/联系电话/类型资料不能为空！');
        }

        $exist = Db::name('aio')->where("mac='" . $data['mac'] . "'")->select();
        if (!empty($exist)) {
            $this->response(-1, '已存在相同MAC地址的一体机数据！');
        }

        $data['post_address'] = input('param.post_address', '');
        $data['post_time'] = input('param.post_time', 0);
        //$data['firmware'] = input('param.firmware', '');
        $data['color'] = input('param.color', '');
        $data['the_type'] = input('param.the_type', 0);
        $data['canal_id'] = input('param.canal_id', 0);
        $data['transport'] = input('param.transport', '');
        $data['shipping_address'] = input('param.shipping_address', '');
        $data['remark'] = input('param.remark', '');
        Db::name('aio')->insert($data);

        if (!empty($school_id)) {
            $result = $this->upAioCount($school_id);
            if ($result['code']) {
                $this->response(1, '新增成功！');
            } else {
                $this->response($result['code'], $result['msg']);
            }
        } else {
            $this->response(1, '新增成功！');
        }
    }

    /**
     * @api {post} /data/AioData/getAioDataById 一体机数据
     * @apiVersion 1.0.0
     * @apiName getAioDataById
     * @apiGroup AioData
     * @apiDescription 一体机数据（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} id 一体机数据ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getAioDataById()
    {
        $id = input('param.id', 0);

        $rs = Db::name('aio')->alias('a')->join('aio_survival b', 'a.mac=b.mac_address', 'left')
            ->where("id=$id")->find();
        if (empty($rs)) {
            $this->response(-1, '您查询的资料不存在！');
        } else {
            $rs['online'] = caculateSurvival($rs);
            $rs['ver_name'] = $rs['app_name'];
            $rs['ver_num'] = $rs['app_version'];
        }
        $this->response(1, '成功', $rs);
    }

    /**
     * @api {post} /data/AioData/editAioData 编辑一体机数据
     * @apiVersion 1.0.0
     * @apiName editAioData
     * @apiGroup AioData
     * @apiDescription 编辑一体机数据（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} id 一体机数据ID.
     * @apiParam {int} province_id 省份ID.
     * @apiParam {int} city_id 城市ID.
     * @apiParam {int} district_id 区域ID.
     * @apiParam {int} school_id 学校ID.
     * @apiParam {int} status 状态（1:正常使用,2:未绑定,3:报废,4:维修）.
     * @apiParam {int} manufact_id 厂商ID.
     * @apiParam {String} mac MAC地址.
     * @apiParam {String} in_charge 负责人.
     * @apiParam {String} address_man 收件人.
     * @apiParam {String} phone 联系电话.
     * @apiParam {int} use_type 类型.
     * @apiParam {String} post_address 发货地.
     * @apiParam {String} post_time 发货时间.
     * @apiParam {String} firmware 固件型号.
     * @apiParam {String} color 颜色.
     * @apiParam {int} the_type 样式.
     * @apiParam {int} canal_id 渠道商ID.
     * @apiParam {String} transport 运输方式.
     * @apiParam {String} shipping_address 收货地址.
     * @apiParam {String} remark 备注.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function editAioData()
    {
        $data['id'] = input('param.id', 0);
        $rs = Db::name('aio')->where("id=" . $data['id'])->find();
        if (empty($rs)) {
            $this->response(-1, '您查询的资料不存在！');
        }
        $init_school_id = $rs['school_id'];

        $data['province'] = $province_id = input('param.province_id', 0);
        $data['city'] = $city_id = input('param.city_id', 0);
        $data['county'] = $district_id = input('param.district_id', 0);
        $data['school_id'] = $school_id = input('param.school_id', 0);
        $data['status'] = $status = input('param.status', 0);
        $status_arr = self::$normal_status;
        if (!empty($status) && in_array($status, $status_arr)) {
            if (empty($province_id) || empty($city_id) || empty($district_id) || empty($school_id)) {
                $this->response(-1, '学校资料不能为空！');
            }
        }

        $data['manufacturer_id'] = $manufact_id = input('param.manufact_id', 0);
        $data['mac'] = $mac = strtoupper(input('param.mac', ''));
        $data['in_charge'] = $in_charge = input('param.in_charge', '');
        $data['address_man'] = $address_man = input('param.address_man', '');
        $data['phone'] = $phone = input('param.phone', '');
        $data['use_type'] = $use_type = input('param.use_type', 0);
        if (empty($manufact_id) || empty($mac) || empty($in_charge)
            || empty($address_man) || empty($phone) || empty($use_type)
        ) {
            $this->response(-1, '厂商/MAC地址/负责人/收件人/联系电话/类型资料不能为空！');
        }

        $exist = Db::name('aio')->where("mac='" . $data['mac'] . "' and id!=" . $data['id'])->select();
        if (!empty($exist)) {
            $this->response(-1, '您输入的MAC地址重复！');
        }

        $data['post_address'] = input('param.post_address', '');
        $data['post_time'] = input('param.post_time', 0);
        //$data['firmware'] = input('param.firmware', '');
        $data['color'] = input('param.color', '');
        $data['the_type'] = input('param.the_type', 0);
        $data['canal_id'] = input('param.canal_id', 0);
        $data['transport'] = input('param.transport', '');
        $data['shipping_address'] = input('param.shipping_address', '');
        $data['remark'] = input('param.remark', '');
        Db::name('aio')->update($data);

        $msg = '';
        if ($init_school_id != $school_id) {
            $init_school_code = $school_code = 1;
            if (!empty($init_school_id)) {
                $result = $this->upAioCount($init_school_id);
                $init_school_code = $result['code'];
                $msg .= $result['msg'];
            }
            if (!empty($school_id)) {
                $result = $this->upAioCount($school_id);
                $school_code = $result['code'];
                $msg .= $result['msg'];
            }
            if ($init_school_code != 1 || $school_code != 1) {
                $this->response(-1, $msg);
            } else {
                $this->response(1, '编辑成功！');
            }
        } else {
            if ($init_school_id != 0) {
                $result = $this->upAioCount($init_school_id);
                if ($result['code']) {
                    $this->response(1, '编辑成功！');
                } else {
                    $this->response($result['code'], $result['msg']);
                }
            }
            $this->response(1, '编辑成功！');
        }
    }

    public function upAioCount($school_id)
    {
        $where['school_id'] = $school_id;
        $where['status'] = ['in', self::$normal_status];
        $cnt = Db::name('aio')->where($where)->distinct(true)->field('mac')->count();

        /*
        $base_api = config('base_api');
        $url =  $base_api.'/api/Member/upAioCount';
        */
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/upAioCount';
        $param['school_id'] = $school_id;
        $param['aio_cnt'] = $cnt;
        $result = curl_api($url, $param, 'post');
        return $result;
    }

    /**
     * @api {post} /data/AioData/aioList 一体机列表
     * @apiVersion 1.0.0
     * @apiName aioList
     * @apiGroup AioData
     * @apiDescription 一体机列表（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} keyword 查询关键字.
     * @apiParam {int} school_id 学校ID.
     * @apiParam {int} status 状态（0:全部 1:正常使用,2:未绑定,3:报废,4:维修）.
     * @apiParam {int} page 页数,默认1
     * @apiParam {int} pagesize 每页数量,默认20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function aioList()
    {
        $keyword = input('param.keyword', '');
        $school_id = input('param.school_id', '');
        $status = input('param.status', 0);
        $page = input('param.page', 1);
        $pagesize = input('param.pagesize', 20);

        empty($page) && $page = 1;
        empty($pagesize) && $pagesize = 20;

        $where = [];
        !empty($status) && $where['status'] = ['in', $status];
        !empty($school_id) && $where['school_id'] = $school_id;

        $cnt = $this->getListQuery($where, $page, $pagesize, 1, $keyword);
        $rs = $this->getListQuery($where, $page, $pagesize, 0, $keyword);
        $result['count'] = $cnt;
        $result['page_num'] = ceil($cnt / $pagesize);
        $result['pagesize'] = $pagesize;
        $result['list'] = [];
        if (!empty($rs)) {
            $datas = [];
            $schoolInfoArr = [];

            foreach ($rs as $row) {
                $data = $row;
                $row_school_id = $row['school_id'];
                $this->getSchoolInfo($row_school_id, $schoolInfoArr);
                $schoolData = $schoolInfoArr[$row_school_id];
                $data['province'] = $schoolData['sch_province'];
                $data['city'] = $schoolData['sch_city'];
                $data['county'] = $schoolData['sch_district'];
                $data['school'] = $schoolData['school_name'];
                $data['type'] = self::$use_labels[$row['use_type']];
                $data['status'] = self::$status_labels[$row['status']];
                $data['online'] = caculateSurvival($data);
                if ($data['online'] == '0小时') {
                    $data['close'] = 0;
                } else {
                    $data['close'] = 1;
                }
                $data['ver_name'] = $row['app_name'];
                $data['ver_num'] = $row['app_version'];
                $datas[] = $data;
            }
            $result['list'] = $datas;
        }
        $this->response(1, '成功', $result);
    }

    public function getListQuery($where, $page, $pagesize, $count_yn=0, $keyword='')
    {
        $qr = Db::name('aio')->alias('a')->join('aio_survival b', 'a.mac=b.mac_address', 'left');
        !empty($where) && $qr->where($where);

        if (!empty($keyword)) {
            $school_api = config('school_api');
            $url = $school_api . '/api/SchoolManage/getList';
            $param['keyword'] = $keyword;
            $param['pagesize'] = 2000;
            $param['field'] = 'school_id,school_name';
            $schoolRs = curl_api($url, $param, 'post');
            $school_ids = [];
            if ($schoolRs['code'] && !empty($schoolRs['data']['list'])) {
                foreach ($schoolRs['data']['list'] as $school_info) {
                    $school_ids[] = $school_info['school_id'];
                }
            }
            !empty($school_ids) && $search_arr['school_id'] = ['in', $school_ids];
            $search_arr['mac'] = ['like', '%' . $keyword . '%'];
            /*
            empty($school_ids) && $school_ids = [0];
            $search_arr = [
                'school_id' => ['in', $school_ids],
                'mac' => ['like', '%' . $keyword . '%'],
            ];
            */
            $qr->where(function ($query) use ($search_arr) {
                $query->whereOr($search_arr);
            });
        }
        if ($count_yn) {
            $rs = $qr->count();
        } else {
            $rs = $qr->limit($pagesize * ($page - 1), $pagesize)->order('a.id desc')->select();
        }
        return $rs;
    }

    public function getSchoolInfo($school_id, &$schoolInfoArr)
    {
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getInfo';

        if (empty($schoolInfoArr[$school_id])) {
            $param['school_id'] = $school_id;
            $schoolRs = curl_api($url, $param, 'post');
            if ($schoolRs['code'] && !empty($schoolRs['data'])) {
                $schoolInfo = $schoolRs['data'];
                $schoolInfoArr[$school_id] = [
                    'sch_province' => !empty($schoolInfo['sch_province'])? $schoolInfo['sch_province'] : '',
                    'sch_city' => !empty($schoolInfo['sch_city'])? $schoolInfo['sch_city'] : '',
                    'sch_district' => !empty($schoolInfo['region_name'])? $schoolInfo['region_name'] : '',
                    'school_name' => !empty($schoolInfo['school_name'])? $schoolInfo['school_name'] : '',
                ];
            } else {
                $schoolInfoArr[$school_id] = [
                    'sch_province' => '',
                    'sch_city' => '',
                    'sch_district' => '',
                    'school_name' => '',
                ];
            }
        }
    }

    /**
     * 推送基础,切勿更改
     */
    public function sendPowerOff($aio_mac_id)
    {
        $in_data = array(
            'aio_mac_id' => $aio_mac_id,  //当前登录账号的school_id
            'update_time' => intval(time()) * 1000,  //更新时间 (timestap) 无需更改
            'power_off' => true
        );
        $this->sendPoweroffPushBase($in_data);
    }

    /***
     *  推送方法体,请勿更改
     */
    public function sendPoweroffPushBase($data)
    {
        $post_data = array(
            'data' => array(
                'action' => 'net.zgxyzx.aio.installer.INSTALL_PUSHER',   //推送动作,切勿更改
                'name' => 'aio_installer',   //推送名称，切勿更改
                'aio_installer_data' => $data
            ),
            'expiration_interval' => 30,
        );
        $headers = array(
            'X-LC-Id: 8UfTkIDha6YVQax4LXNipyLy-gzGzoHsz',
            'X-LC-Key: syR9OrzvR02sTCboIXnP9uKX',
            'Content-Type: application/json'
        );
        $url = 'https://leancloud.cn/1.1/push';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result; //返回值输出 (无用,可不输出)  成功返回为后面所示,其余为失败返回  {"objectId":"4OsSJD56zyFmRUrb","created
    }

    /**
     * @api {post} /data/AioData/shutdown 关机
     * @apiVersion 1.0.0
     * @apiName shutdown
     * @apiGroup AioData
     * @apiDescription 关机（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} mac 一体机mac地址.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function shutdown()
    {
        ini_set('max_execution_time', '35');
        $mac = strtoupper(input('param.mac', ''));
        if (empty($mac)) {
            $this->response(-1, "MAC地址不能为空！");
        }
        $mac_info = Db::name('aio_survival')->where("mac_address='$mac'")->find();
        if (empty($mac_info)) {
            $this->response(-1, "没有此MAC地址信息！");
        }

        $data['mac_id'] = $mac_info['mac_id'];
        $data['shutdown_yn'] = 0;
        Db::name('aio_survival')->update($data);

        $this->sendPowerOff($mac);
        $times = 10;
        for ($cnt = $times; $cnt >= 0; $cnt--) {
            $close_yn = Db::name('aio_survival')->where("mac_id=" . $mac_info['mac_id'])->value('shutdown_yn');
            if ($close_yn) {
                $this->response(1, '关机成功');
                break;
            }
            sleep(3);
        }
        $this->response(-1, '请确认机器网络是否正常且为开机状态！');
    }

    /**
     * @api {post} /data/AioData/delete 删除
     * @apiVersion 1.0.0
     * @apiName delete
     * @apiGroup AioData
     * @apiDescription 删除（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} id 一体机数据ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function delete()
    {
        $id = input('param.id', 0);
        if (empty($id)) {
            $this->response(-1, '请选择要删除的资料！');
        }

        $rs = Db::name('aio')->where("id=" . $id)->find();
        if (empty($rs)) {
            $this->response(-1, '您查询的资料不存在！');
        }
        Db::name('aio')->delete($id);

        if (!empty($rs['school_id'])) {
            $result = $this->upAioCount($rs['school_id']);
            if ($result['code']) {
                $this->response(1, '删除成功！');
            }
        }
        $this->response(1, '删除成功！');
    }

    /**
     * @api {post} /data/AioData/batchDelete 批量删除
     * @apiVersion 1.0.0
     * @apiName batchDelete
     * @apiGroup AioData
     * @apiDescription 批量删除（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} ids 一体机数据ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function batchDelete()
    {
        $id = input('param.id', '');
        if (empty($id)) {
            $this->response(-1, '请选择要删除的资料！');
        }
        $ids = explode(',', trim($id));

        $where['id'] = ['in', $ids];
        $rs = Db::name('aio')->where($where)->select();
        $school_ids = [];
        if (!empty($rs)) {
            foreach ($rs as $row) {
                if (!in_array($row['school_id'], $school_ids)) {
                    $school_ids[] = $row['school_id'];
                }
            }
        }

        Db::name('aio')->delete($ids);
        if (!empty($school_ids)) {
            foreach ($school_ids as $school_id) {
                $this->upAioCount($school_id);
            }
        }

        $this->response(1, '删除成功！');
    }

    /**
     * @api {post} /data/AioData/importAioData 导入一体机数据
     * @apiVersion 1.0.0
     * @apiName importAioData
     * @apiGroup AioData
     * @apiDescription 导入一体机数据（郑陈菲）
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} token 用户的token
     * @apiParam {String} file xls文件url地址.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function importAioData()
    {
        $file = input('file');
        $path = './static';
        if (empty($file)) {
            $this->response(-1, '请上传导入文档！');
        }
        $filename = downloadFile($file, $path);
        if (empty($filename)) {
            $this->response(-1, '导入失败,文件获取失败');
        }
        $excel_data = importExcel($path . '/' . $filename);

        $column_map = self::$import_column_map;
        $not_empty_column = ['学校省份', '学校地区', '学校区县', '学校名称', '绑定及状态', '厂商', 'MAC地址', '负责人', '收件人', '联系电话', '类型'];
        foreach ($not_empty_column as $column) {
            if (!in_array($column, $excel_data['title'])) {
                $this->response(-1, '导入模板不正确！');
            }
        }
        foreach ($excel_data['title'] as $key => $field_name) {
            if (empty($column_map[$field_name])) {
                $this->response(-1, '导入模板不正确！');
            }
            $field_map[$key] = $column_map[$field_name];
        }
        $datas = [];
        if (!empty($excel_data['data'])) {
            $i = 2;
            foreach ($excel_data['data'] as $row) {
                $data['line'] = $i++;
                foreach ($row as $key => $val) {
                    $field = $field_map[$key];
                    $data[$field] = trim($val);
                }
                $datas[] = $data;
            }
        }
        $result = $this->doImportAioData($datas);
        $this->response($result['code'], $result['msg'], $result['data']);
    }

    public function doImportAioData($datas)
    {
        $result['data'] = '';
        if (!empty($datas)) {
            //已有厂商
            $exist_manufactures = [];
            $exist_manufacture_rs = Db::name('aio_manufacturer')->select();
            if (!empty($exist_manufacture_rs)) {
                foreach ($exist_manufacture_rs as $row) {
                    $exist_manufactures[$row['name']] = $row['id'];
                }
            }
            //已有渠道商
            $exist_canals = [];
            $exist_canal_rs = Db::name('aio_canal')->select();
            if (!empty($exist_canal_rs)) {
                foreach ($exist_canal_rs as $row) {
                    $exist_canals[$row['name']] = $row['id'];
                }
            }
            //已有的一体机数据
            $exist_aios = [];
            $exist_aio_rs = Db::name('aio')->select();
            if (!empty($exist_aio_rs)) {
                foreach ($exist_aio_rs as $row) {
                    $exist_aios[$row['mac']] = $row['id'];
                }
            }
            //已有的省市县、学校信息
            $regions = $schools = [];
            $base_api = config('base_api');
            $url = $base_api . '/api/region/getAllRegion';
            $region_rs = curl_api($url, [], 'post');
            if (!empty($region_rs['data'])) {
                foreach ($region_rs['data'] as $row) {
                    $regions[$row['region_name']][] = $row['region_id'];
                }
            }

            $flag = 1;
            $result_add_datas = [];
            $result_up_datas = [];
            $unique_keys = [];
            $fnum = 0;
            $snum = 0;
            $fail_file = "./static/import_aiodata_fail_" . $this->uid . ".txt";
            file_exists($fail_file) && unlink($fail_file);
            foreach ($datas as $row) {
                $init_row = $row;
                $unique_key = $init_row['mac'];
                if (!empty($unique_keys) && in_array($unique_key, $unique_keys)) {
                    $flag = 0;
                    $fdetail[] = "第" . $row['line'] . "行：已存在相同MAC地址的导入资料；";
                    $fnum++;
                    continue;
                }
                $unique_keys[] = $unique_key;
                $valid_msg = $this->checkImport($exist_manufactures, $exist_canals, $exist_aios, $regions, $schools, $row);
                if ($valid_msg['flag'] == 'error') {
                    $flag = 0;
                    $fdetail[] = "第" . $row['line'] . "行：" . $valid_msg['detail'];
                    $fnum++;
                }
                if ($flag) {
                    unset($row['line']);
                    if ($valid_msg['repeat']) {
                        $row['id'] = $valid_msg['repeat'];
                        $result_up_datas[] = $row;
                    } else {
                        $result_add_datas[] = $row;
                    }
                    $snum++;
                }
            }
            if ($flag) {
                $exist_where['mac'] = ['in', $unique_keys];
                $schools = [];
                if (!empty($unique_keys)) {
                    $schools = Db::name('aio')->distinct(true)->field('school_id')->where($exist_where)->column('school_id');
                }
                if (!empty($result_add_datas)) {
                    Db::name('aio')->insertAll($result_add_datas);
                }
                if (!empty($result_up_datas)) {
                    foreach ($result_up_datas as $row) {
                        Db::name('aio')->update($row);
                    }
                }
                if (!empty($unique_keys)) {
                    $new_schools = Db::name('aio')->distinct(true)->field('school_id')->where($exist_where)->column('school_id');
                    $schools = array_unique(array_merge($schools, $new_schools));
                }
                if (!empty($schools)) {
                    foreach ($schools as $school_id) {
                        $school_id > 0 && $this->upAioCount($school_id);
                    }
                }
                $result['code'] = 1;
                $result['msg'] = '成功导入' . $snum . '笔资料！';
            } else {
                $fp = fopen($fail_file, "w");
                $error_details = "有" . $fnum . "笔资料有误：\r\n" . implode("\r\n", $fdetail);
                $error_details = "\xff\xfe" . iconv('utf-8', 'utf-16le', $error_details);
                fwrite($fp, $error_details);
                fclose($fp);
                $result['code'] = -1;
                $result['msg'] = '导入失败，请调整资料后重新导入！';
                $result['data'] = ['file' => config('ddzx_api') . $fail_file];
            }
            return $result;
        } else {
            $result['code'] = '1';
            $result['msg'] = '无资料需要导入!';
            return $result;
        }
    }

    private function checkImport(&$exist_manufactures, &$exist_canals, $exist_aios, $regions, &$schools, &$data)
    {
        $msg['flag'] = 'ok';
        $msg['detail'] = '';
        $msg['repeat'] = 0;

        //检查必填字段
        if (empty($data['manufacturer_id']) || empty($data['mac']) || empty($data['in_charge'])
            || empty($data['address_man']) || empty($data['phone']) || empty($data['use_type'])
            || empty($data['status'])
        ) {
            $msg['flag'] = 'error';
            $msg['detail'] .= '绑定及状态、厂商、MAC地址、负责人、收件人、联系电话、类型为必填字段；';
        }

        //检查绑定状态是否正确
        $status_map = array_flip(self::$status_labels);
        if (empty($status_map[$data['status']])) {
            $msg['flag'] = 'error';
            $msg['detail'] .= '请填写正确的绑定及状态；';
        } else {
            $data['status'] = $status_map[$data['status']];
        }

        //检查MAC地址格式
        if (strlen($data['mac'])!=12 || !preg_match("/^[a-zA-Z0-9]+$/i", $data['mac'])) {
            $msg['flag'] = 'error';
            $msg['detail'] .= 'MAC地址格式不正确；';
        }

        //检查联系电话格式
        if (strstr($data['phone'], '-')) {
            $msg['flag'] = 'error';
            $msg['detail'] .= '电话不能包含-；';
        }

        //检查类型是否正确
        $use_map = array_flip(self::$use_labels);
        if (empty($use_map[$data['use_type']])) {
            $msg['flag'] = 'error';
            $msg['detail'] .= '请填写正确的类型；';
        } else {
            $data['use_type'] = $use_map[$data['use_type']];
        }

        //检查样式是否正确
        $type_map = array_flip(self::$type_labels);
        if (!empty($data['the_type'])) {
            if (empty($type_map[$data['the_type']])) {
                $msg['flag'] = 'error';
                $msg['detail'] .= '请填写正确的样式；';
            } else {
                $data['the_type'] = $type_map[$data['the_type']];
            }
        } else {
            $data['the_type'] = 1;
        }

        //若状态为正常使用、维修，省市县与学校非空
        if (in_array($data['status'], self::$normal_status)
            && (empty($data['province']) || empty($data['city']) || empty($data['county']) || empty($data['school_id']))
        ) {
            $msg['flag'] = 'error';
            $msg['detail'] .= '绑定及状态为正常使用或维修时，请填写学校的省份、地区、区县及学校名称资料；';
        }

        //若状态非正常使用、维修，省市县与学校置为0
        if (!in_array($data['status'], self::$normal_status)) {
            $data['province'] = $data['city'] = $data['county'] = $data['school_id'] = 0;
        }

        //检查省市县、学校资料是否存在
        if ($data['province']) {
            if (empty($regions[$data['province']])) {
                $msg['flag'] = 'error';
                $msg['detail'] .= '学校省份不正确；';
            }
            if (empty($regions[$data['city']])) {
                $msg['flag'] = 'error';
                $msg['detail'] .= '学校地区不正确；';
            }
            if (empty($regions[$data['county']])) {
                $msg['flag'] = 'error';
                $msg['detail'] .= '学校区县不正确；';
            }
            if (empty($schools[$data['school_id']])) {
                $school_api = config('school_api');
                $url = $school_api . '/api/SchoolManage/getList';
                $param['keyword'] = $data['school_id'];
                $param['field'] = 'school_id,school_name,enterflag,province_id,city_id,district_id';
                $result = curl_api($url, $param, 'post');
                $flag = 0;
                if ($result['code'] && !empty($result['data']['list'])) {
                    foreach ($result['data']['list'] as $school_info) {
                        $schools[$school_info['school_name']] = [
                            'school_id' => $school_info['school_id'],
                            'enterflag' => $school_info['enterflag'],
                            'province_id' => $school_info['province_id'],
                            'city_id' => $school_info['city_id'],
                            'district_id' => $school_info['district_id'],
                        ];
                        if ($school_info['school_name'] == $data['school_id']) {
                            $flag = 1;
                            break;
                        }
                    }
                }
                if (!$flag) {
                    $msg['flag'] = 'error';
                    $msg['detail'] .= '学校名称不正确；';
                }
            }
            if ($schools[$data['school_id']]['enterflag'] != 2) {
                $msg['flag'] = 'error';
                $msg['detail'] .= '学校未入驻；';
            }
            if (!in_array($schools[$data['school_id']]['province_id'], $regions[$data['province']])) {
                $msg['flag'] = 'error';
                $msg['detail'] .= '此学校对应的省份不正确；';
            }
            if (!in_array($schools[$data['school_id']]['city_id'], $regions[$data['city']])) {
                $msg['flag'] = 'error';
                $msg['detail'] .= '此学校对应的地区不正确；';
            }
            //if ($schools[$data['school_id']]['district_id'] != $regions[$data['county']]) {
            if (!in_array($schools[$data['school_id']]['district_id'], $regions[$data['county']])) {
                $msg['flag'] = 'error';
                $msg['detail'] .= '此学校对应的区县不正确；';
            }
        }

        if (!empty($msg['detail'])) {
            return $msg;
        }

        if ($data['province']) {
            /*
            $data['province'] = $regions[$data['province']];
            $data['city'] = $regions[$data['city']];
            $data['county'] = $regions[$data['county']];
            $data['school_id'] = $schools[$data['school_id']]['school_id'];
            */
            $data['province'] = $schools[$data['school_id']]['province_id'];
            $data['city'] = $schools[$data['school_id']]['city_id'];
            $data['county'] = $schools[$data['school_id']]['district_id'];
            $data['school_id'] = $schools[$data['school_id']]['school_id'];
        }

        //检查厂商资料是否存在
        if (empty($exist_manufactures[$data['manufacturer_id']])) {
            $manu_id = Db::name('aio_manufacturer')->insert(['name' => $data['manufacturer_id']], false, true);
            $exist_manufactures[$data['manufacturer_id']] = $manu_id;
        }
        $data['manufacturer_id'] = $exist_manufactures[$data['manufacturer_id']];

        //检查渠道商资料是否存在
        if (!empty($data['canal_id'])) {
            if (empty($exist_canals[$data['canal_id']])) {
                $canal_id = Db::name('aio_canal')->insert(['name' => $data['canal_id']], false, true);
                $exist_canals[$data['canal_id']] = $canal_id;
            }
            $data['canal_id'] = $exist_canals[$data['canal_id']];
        } else {
            $data['canal_id'] = 0;
        }

        $data['mac'] = strtoupper($data['mac']);
        empty($data['post_address']) && $data['post_address'] = '';
        if (!empty($data['post_time'])) {
            $data['post_time'] = strtotime($data['post_time']);
        } else {
            $data['post_time'] = 0;
        }
        //empty($data['firmware']) && $data['firmware'] = '';
        empty($data['color']) && $data['color'] = '';
        empty($data['place']) && $data['place'] = '';
        empty($data['transport']) && $data['transport'] = '';
        empty($data['shipping_address']) && $data['shipping_address'] = '';
        empty($data['remark']) && $data['remark'] = '';

        //检查是否已存在资料
        if (!empty($exist_aios[$data['mac']])) {
            $msg['repeat'] = $exist_aios[$data['mac']];
        }
        return $msg;
    }

    /**
     * @api {post} /data/AioData/exportAioData 一体机数据导出
     * @apiVersion 1.0.0
     * @apiName exportAioData
     * @apiGroup AioData
     * @apiDescription 一体机数据导出（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} ids 一体机数据ID
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function exportAioData()
    {
        $ids = input('param.ids', '');

        $expTitle = "AioData_" . $this->uid . "_" . time();
        $expCellName = array(
            array('province', '学校省份'),
            array('city', '学校地区'),
            array('county', '学校区县'),
            array('school', '学校名称'),
            array('status', '绑定及状态'),
            array('manufacturer', '厂商'),
            array('mac', 'MAC地址'),
            array('in_charge', '负责人'),
            array('address_man', '收件人'),
            array('phone', '联系电话'),
            array('use_type', '类型'),
            array('ver_name', '版本'),
            array('ver_num', '版本号'),
            array('post_address', '发货地'),
            array('post_time', '发货时间'),
            //array('firmware', '固件型号'),
            array('rom_version', '固件型号'),
            array('color', '颜色'),
            array('the_type', '样式'),
            array('place', '摆放位置'),
            array('canal', '渠道商'),
            array('transport', '运输方式'),
            array('shipping_address', '地址'),
            array('online', '开机时长'),
            array('remark', '备注'),
        );
        $where = [];
        !empty($ids) && $where['id'] = array('in', explode(',', $ids));

        $qr = Db::name('aio')->alias('a')->join('aio_survival b', 'a.mac=b.mac_address', 'left');
        !empty($where) && $qr->where($where);
        $rs = $qr->order('a.id desc')->select();

        $expTableData = [];
        if (empty($rs)) {
            $this->response(-1, "没有数据可导出！");
        }
        $manu_rs = Db::name('aio_manufacturer')->select();
        $manu_datas = [];
        if (!empty($manu_rs)) {
            foreach ($manu_rs as $row) {
                $manu_datas[$row['id']] = $row['name'];
            }
        }

        $canal_rs = Db::name('aio_canal')->select();
        $canal_datas = [];
        if (!empty($canal_rs)) {
            foreach ($canal_rs as $row) {
                $canal_datas[$row['id']] = $row['name'];
            }
        }

        $schoolInfoArr = [];
        foreach ($rs as $row) {
            $row_school_id = $row['school_id'];
            if ($row_school_id) {
                $this->getSchoolInfo($row_school_id, $schoolInfoArr);
                $schoolData = $schoolInfoArr[$row_school_id];
                $data['province'] = $schoolData['sch_province'];
                $data['city'] = $schoolData['sch_city'];
                $data['county'] = $schoolData['sch_district'];
                $data['school'] = $schoolData['school_name'];
            } else {
                $data['province'] = $data['city'] = $data['county'] = $data['school'] = '';
            }
            $data['status'] = self::$status_labels[$row['status']];
            $data['manufacturer'] = !empty($manu_datas[$row['manufacturer_id']])? $manu_datas[$row['manufacturer_id']] : '';
            $data['mac'] = $row['mac'];
            $data['in_charge'] = $row['in_charge'];
            $data['address_man'] = $row['address_man'];
            $data['phone'] = $row['phone'];
            $data['use_type'] = self::$use_labels[$row['use_type']];
            $data['ver_name'] = $row['app_name'];
            $data['ver_num'] = $row['app_version'];
            $data['post_address'] = $row['post_address'];
            $data['post_time'] = !empty($row['post_time'])? date("Y-m-d H:i:s", $row['post_time']) : '';
            //$data['firmware'] = $row['firmware'];
            $data['rom_version'] = $row['rom_version'];
            $data['color'] = $row['color'];
            $data['the_type'] = $row['the_type']? self::$type_labels[$row['the_type']] : '';
            $data['place'] = $row['place'];
            $data['canal'] = !empty($canal_datas[$row['canal_id']])? $canal_datas[$row['canal_id']] : '';
            $data['transport'] = $row['transport'];
            $data['shipping_address'] = $row['shipping_address'];
            $data['online'] = caculateSurvival($row);
            $data['remark'] = $row['remark'];
            $expTableData[] = $data;
        }
        exportExcel($expTitle, $expCellName, $expTableData);
    }
}