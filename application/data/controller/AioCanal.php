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

class AioCanal extends Admin
{
    /**
     * @api {post} /data/AioCanal/canalList 渠道列表
     * @apiVersion 1.0.0
     * @apiName canalList
     * @apiGroup AioCanal
     * @apiDescription 渠道列表（郑陈菲）
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} page 页数,默认1
     * @apiParam {int} pagesize 每页数量,默认20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function canalList()
    {
        $page = input('param.page', 1);
        $pagesize = input('param.pagesize', 20);
        $rs = Db::name('aio_canal')->limit($pagesize * ($page - 1), $pagesize)->order('id desc')->select();
        $cnt = count($rs);
        $result['count'] = $cnt;
        $result['page_num'] = ceil($cnt / $pagesize);
        $result['pagesize'] = $pagesize;
        $result['list'] = [];
        if (!empty($rs)) {
            $result['list'] = $rs;
        }
        $this->response(1, '成功', $result);
    }

    /**
     * @api {post} /data/AioCanal/setCanal 提交渠道商
     * @apiVersion 1.0.0
     * @apiName setCanal
     * @apiGROUP AioCanal
     * @apiDescription 提交渠道商
     * @apiParam {String} token 已登录账号的token
     * @apiParam {String} name 渠道商名称(多个用',' 分割)
     * @apiParam {Int} id 渠道商ID(多个用',' 分割)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     *
     */
    public function setCanal()
    {
        $info = input('param.');
        if (empty($info['id']) && empty($info['name'])) {
            Db::name('aio_canal')->where('1=1')->delete();
            $this->response('1', '保存成功！');
        }
        $names = explode(',', $info['name']);
        $ids = explode(',', $info['id']);
        $cnt = count($ids);
        if(count($names) != $cnt) {
            $this->response('-1', '数据请求有误！');
        }

        $rs = Db::name('aio_canal')->select();
        $old_data = $old_ids = [];
        $last_names = $last_ids = [];
        $update_datas = $insert_datas = [];
        if (!empty($rs)) {
            foreach ($rs as $row) {
                $old_data[$row['id']] = $row['name'];
                $old_ids[] = $row['id'];
            }
        }
        for ($i=0; $i<$cnt; $i++) {
            $id = $ids[$i];
            $name = $names[$i];
            if (in_array($name, $last_names)) {
                $this->response('-1', '渠道商：'.$name.'设定重复！');
            } else {
                $last_names[] = $name;
            }
            if ($id != -1) {
                if (!empty($old_data) && $old_data[$id] != $name) {
                    $update_datas[] = [
                        'id' => $id,
                        'name' => $name,
                    ];
                }
                $last_ids[] = $id;
            }
            if ($id == -1) {
                $insert_datas[] = ['name' => $name];
            }
        }
        $diff_ids = array_diff($old_ids, $last_ids);

        $flag1 = $flag2 = $flag3 = 1;
        if (!empty($update_datas)) {
            foreach ($update_datas as $data) {
                $rs = Db::name('aio_canal')->update($data);
                if ($rs === false) {
                    $flag1 = 0;
                }
            }
        }
        if (!empty($insert_datas)) {
            $flag2 = Db::name('aio_canal')->insertAll($insert_datas);
        }
        if($diff_ids) {
            $flag3 = DB::name('aio_canal')->where('id', 'in', $diff_ids)->delete();
        }
        if ($flag1 !==false || $flag2 !==false || $flag3 !==false ) {
            $this->response('1', '保存成功');
        } else {
            $this->response('-1', '保存失败');
        }
    }
}