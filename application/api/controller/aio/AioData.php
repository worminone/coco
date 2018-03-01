<?php
namespace app\api\controller\aio;

use think\Db;
use app\common\controller\Api;

class AioData extends Api
{
    /**
     * @api {post} /api/aio.AioData/getAioDataByMac 由MAC获取一体机数据
     * @apiVersion 1.0.0
     * @apiName getAioDataByMac
     * @apiGroup AIO
     * @apiDescription 由MAC获取一体机数据（郑陈菲）
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} token 用户的token
     * @apiParam {string} mac MAC地址
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getAioDataByMac()
    {
        $mac = strtoupper(input('param.mac', ''));
        if (empty($mac)) {
            $this->response(-1, 'MAC地址不能为空！');
        }

        $rs = Db::name('aio')->alias('a')->join('aio_survival b', 'a.mac=b.mac_address', 'left')
            ->where("mac='$mac'")->find();
        if (empty($rs)) {
            $this->response(-1, '您查询的机器未注册！');
        } else {
            $school_api = config('school_api');
            $url =  $school_api.'/api/SchoolManage/getList';
            $param['school_id'] = $rs['school_id'];
            $param['field'] = 'school_id,school_name';
            $result = curl_api($url, $param, 'post');
            $school_name = '';
            if ($result['code'] && !empty($result['data']['list'])) {
                $school_name = $result['data']['list'][0]['school_name'];
            }

            $rs['school_name'] = $school_name;
            $rs['online'] = caculateSurvival($rs);
            $rs['ver_name'] = $rs['app_name'];
            $rs['ver_num'] = $rs['app_version'];
        }
        $this->response(1, '成功', $rs);
    }

    /**
     * @api {post} /api/aio.AioData/syncAioData 同步一体机数据
     * @apiVersion 1.0.0
     * @apiName syncAioData
     * @apiGroup AIO
     * @apiDescription 同步一体机数据（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} id 一体机数据ID.
     * @apiParam {String} mac MAC地址.
     * @apiParam {int} the_type 样式(1:站立,2:壁挂).
     * @apiParam {String} place 学校放置位置.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function syncAioData()
    {
        $data['id'] = input('param.id', 0);
        $data['mac'] = strtoupper(input('param.mac', ''));
        $data['the_type'] = input('param.the_type', 0);
        $data['place'] = input('param.place', '');

        if (empty($data['id']) || empty($data['mac']) || empty($data['the_type']) || empty($data['place'])) {
            $this->response(-1, 'id/MAC地址/样式/摆放位置不能为空！');
        }

        if (!in_array($data['the_type'], [1, 2])) {
            $this->response(-1, '样式资料不正确！');
        }

        $rs = Db::name('aio')->where("id=".$data['id'])->find();
        if (empty($rs) || (!empty($rs) && $rs['mac'] != $data['mac'])) {
            $this->response(-1, '您查询的资料不存在！');
        }
        Db::name('aio')->update($data);
        $this->response(1, '同步成功！');
    }

    /**
     * @api {post} /api/aio.AioData/getAioLastAdminor 一体机最后登入的管理员
     * @apiVersion 1.0.0
     * @apiName getAioLastAdminor
     * @apiGroup AIO
     * @apiDescription 一体机最后登入的管理员（郑陈菲）
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} token 用户的token
     * @apiParam {string} mac MAC地址
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getAioLastAdminor()
    {
        $mac = strtoupper(input('param.mac', ''));
        if (empty($mac)) {
            $this->response(-1, 'MAC地址不能为空！');
        }

        $data = [];
        $school_id = Db::name('aio')->where("mac='$mac'")->value('school_id');
        if (empty($school_id)) {
            $this->response(-1, '您查询的机器未注册！');
        } else {
            $base_api = config('base_api');
            $url =  $base_api.'/api/user/getLastLogin';
            $param['utype'] = 99;
            $param['school_id'] = $school_id;
            $param['auditing_flag'] = 2;
            $param['status'] = 0;
            $param['get_field'] = 'user_name,password';
            $param['page'] = 1;
            $param['pagesize'] = 1;
            $result = curl_api($url, $param, 'post');
            if ($result['code'] && !empty($result['data']['list'])) {
                $data['user_name'] = $result['data']['list'][0]['user_name'];
                $data['password'] = $result['data']['list'][0]['password'];
            } else {
                $this->response(-1, '无学校管理员信息！');
            }
        }
        $this->response(1, '成功', $data);
    }

    /**
     * @api {post} /api/aio.AioData/getAioDataBySchid 由sch_id获取一体机数据
     * @apiVersion 1.0.0
     * @apiName getAioDataBySchid
     * @apiGroup AIO
     * @apiDescription 由sch_id获取一体机数据（郑陈菲）
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} token 用户的token
     * @apiParam {string} school_id 学校ID
     * @apiParam {int} page 页数,默认1
     * @apiParam {int} pagesize 每页数量,默认20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getAioDataBySchid()
    {
        $school_id = input('param.school_id', '');
        $page = input('param.page', 1);
        $pagesize = input('param.pagesize', 20);

        empty($page) && $page = 1;
        empty($pagesize) && $pagesize = 20;

        if (empty($school_id)) {
            $this->response(-1, '学校ID不能为空！');
        }

        $start = ($page - 1) * $pagesize;
        $limit = $start . ',' . $pagesize;
        $cnt = Db::name('aio')->alias('a')->join('aio_survival b', 'a.mac=b.mac_address', 'left')
            ->where("school_id=$school_id")->count();
        $rs = Db::name('aio')->alias('a')->join('aio_survival b', 'a.mac=b.mac_address', 'left')
            ->where("school_id=$school_id")->limit($limit)->select();
        $result['count'] = $cnt;
        $result['page_num'] = ceil($cnt / $pagesize);
        $result['pagesize'] = $pagesize;
        $result['list'] = [];
        if (empty($rs)) {
            $this->response(-1, '您查询的机器未注册！');
        } else {
            /*
            $school_api = config('school_api');
            $url =  $school_api.'/api/SchoolManage/getList';
            $param['school_id'] = $rs['school_id'];
            $param['field'] = 'school_id,school_name';
            $result = curl_api($url, $param, 'post');
            $school_name = '';
            if ($result['code'] && !empty($result['data']['list'])) {
                $school_name = $result['data']['list'][0]['school_name'];
            }

            $rs['school_name'] = $school_name;
            $rs['online'] = caculateSurvival($rs);
            $rs['ver_name'] = $rs['app_name'];
            $rs['ver_num'] = $rs['app_version'];
            */
            foreach ($rs as $row) {
                $data['id'] = $row['id'];
                $data['mac'] = $row['mac'];
                $data['remark'] = $row['remark'];
                $result['list'][] = $data;
            }
        }
        $this->response(1, '成功', $result);
    }

    /**
     * @api {post} /api/aio.AioData/editAioRemark 编辑一体机备注
     * @apiVersion 1.0.0
     * @apiName editAioRemark
     * @apiGroup AIO
     * @apiDescription 编辑一体机备注（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} id 一体机数据ID.
     * @apiParam {String} mac MAC地址.
     * @apiParam {String} remark 备注.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function editAioRemark()
    {
        $data['id'] = input('param.id', 0);
        $data['mac'] = strtoupper(input('param.mac', ''));
        $data['remark'] = input('param.remark', '');

        if (empty($data['id']) || empty($data['mac']) || empty($data['remark'])) {
            $this->response(-1, 'id/MAC地址/备注不能为空！');
        }

        $rs = Db::name('aio')->where("id=".$data['id'])->find();
        if (empty($rs) || (!empty($rs) && $rs['mac'] != $data['mac'])) {
            $this->response(-1, '您查询的资料不存在！');
        }
        Db::name('aio')->update($data);
        $this->response(1, '更新成功！');
    }
}

