<?php

namespace app\business\model;

use think\Db;
use app\common\controller\Base;
use app\auth\model\Member;
use app\article\model\Term;

class Business
{
    private $sourceArr = array();//商机来源渠道
    private $typeArr = array(
        1 => '内容资源报道合作',
        2 => '市场渠道加盟合作',
        3 => '广告投放商务洽谈',
        4 => '数据资源整合洽谈',
        5 => '大学机构'
    );//合作形式
    private $statusArr = array(0 => '未处理', 1 => '已处理');//处理状态

    function __construct()
    {
        $term = new Term();
        $this->sourceArr = $term->getTermTypeColumnInfo();
    }

    //根据来源名称获得来源id
    public function getSourceIdByName($name)
    {
        $sourceArr = array_flip($this->sourceArr);
        return !empty($sourceArr[$name]) ? $sourceArr[$name] : false;
    }

    //根据类型名称获得类型id
    public function getTypeIdByName($name)
    {
        $typeArr = array_flip($this->typeArr);
        return !empty($typeArr[$name]) ? $typeArr[$name] : false;
    }

    //获取招商列表
    public function getBusinessList($where, $order, $field, $pagesize)
    {
        $baseModel = new Base();
        $memberModel = new Member();
        $list = $baseModel->getPageList('Business', $where, $order, $field, $pagesize);
        if (!empty($list['list'])) {
            foreach ($list['list'] as &$value) {
                $value['source_type_name'] = !empty($this->sourceArr[$value['source_type']]) ? $this->sourceArr[$value['source_type']] : '';
                $value['type_name'] = !empty($this->typeArr[$value['type']]) ? $this->typeArr[$value['type']] : '';
                $value['status_name'] = !empty($this->statusArr[$value['status']]) ? $this->statusArr[$value['status']] : '';
                $value['user_name'] = '';
                unset($value['content']);
                if (!empty($value['uid'])) {
                    $memberInfo = $memberModel->getInfo($value['uid']);
                    if (!empty($memberInfo['true_name'])) {
                        $value['user_name'] = $memberInfo['true_name'];
                    }
                }
            }
        }
        return $list;
    }

    /**
     * 查询招商详情
     * @param $id
     */
    public function getInfo($id)
    {
        $memberModel = new Member();
        $info = DB::name('Business')->where(['id' => $id])->find();
        if (!empty($info)) {
            $info['source_type_name'] = !empty($this->sourceArr[$info['source_type']]) ? $this->sourceArr[$info['source_type']] : '';
            $info['type_name'] = !empty($this->typeArr[$info['type']]) ? $this->typeArr[$info['type']] : '';
            $info['status_name'] = !empty($this->statusArr[$info['status']]) ? $this->statusArr[$info['status']] : '';
            $info['user_name'] = '';
            if (!empty($info['uid'])) {
                $memberInfo = $memberModel->getInfo($info['uid']);
                if (!empty($memberInfo['user_name'])) {
                    $info['user_name'] = $memberInfo['true_name'];
                }
            }
        }
        return $info;
    }

    /**
     * 更新用户回复状态
     * @param $uid //处理者
     * @param $id
     * @return array|false|int|mixed|\PDOStatement|string|\think\Model
     */
    public function updateStatus($uid, $id)
    {
        $info = DB::name('Business')->where(['id' => $id])->find();
        if (!empty($info)) {
            $where['id'] = $id;
            $where['status'] = empty($info['status']) ? 1 : 0;
            $where['uid'] = $uid;
            $info = DB::name('Business')->update($where);
        }
        return $info;
    }
}
