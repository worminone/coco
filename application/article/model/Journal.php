<?php

namespace app\article\model;

use think\Db;

class Journal
{
    //ID 获取 期刊信息
    public function getJournalInfo($id)
    {
        return DB::name('Journal')
            ->where(['id'=>$id])
            ->find();
    }

    //获取期刊类别信息
    public function getJournalType()
    {
        return DB::name('Journal')->column('name', 'id');
    }

    //获取期刊类别列表
    public function getJournalTypeList()
    {
        return DB::name('JournalType')->select();
    }

    //获取期刊信息
    public function getJournalList($id)
    {
        return DB::name('Journal')
            ->where(['id'=>$id])
            ->find();
    }

    //前端获取期刊信息列表
    public function getJournalCoverList($where, $field, $limit, $order)
    {
        return DB::name('Journal')
                ->field($field)
                ->where($where)
                ->limit($limit)
                ->order($order)
                ->select();
    }

    //新增访问量
    public function addViewCount($id)
    {
       return DB::name('Journal')->where(['id'=>$id])->setInc('view_count', 1);
    }
}
