<?php

namespace app\lessonplan\model;

use think\Db;
use \app\lessonplan\model\Menu;

class Combo
{
    public function getComboCount($where)
    {
        $total = Db::name('teaching_combo')->where($where)->count();
        return $total;
    }
    public function getComboData($where, $limit)
    {
        $list = Db::name('teaching_combo')->where($where)->order('id desc')->limit($limit)->select();
        return $list;
    }
    public function getDetail($ids)
    {
        $where['id']=['in',$ids];
        $where['pid']=0;
        $first = Db::name('teaching_catalogue')->field('id,name as label')->where($where)->select();
        foreach ($first as $key=> $val) {
            $where['pid'] = $val['id'];
            $first[$key]['children'] = Db::name('teaching_catalogue')->field('id,pid,name as label')->where($where)->select();
        }
        $num=count($ids);
        $count=count($first);
        $tmp=array();
        foreach ($ids as $k=>$arr) {
            $pid=Db::name('teaching_catalogue')->where("id='$arr'")->value('pid');
            if(!(in_array($pid,$ids)||$pid==0)){
                $tmp[$count]=Db::name('teaching_catalogue')->field('id,pid,name as label')->where("id='$pid'")->find();
                $where['pid']=$pid;
                $tmp[$count++]['children']=Db::name('teaching_catalogue')->field('id,pid,name as label')->where($where)->select();
                $ids[$num++]=$pid;
            }
        }
        $first=array_merge($first,$tmp);
        array_multisort(array_column($first,'id'),SORT_ASC,$first);
        return $first;
    }
    public function getAllDetail($school_id, $id, $first_catalogue)
    {
        $where = [];
        $result = ['list1'=>[],'list2'=>[]];
        if (!empty($id)) {
            $where['id'] = $id;
        }
        if (!empty($first_catalogue)) {
            $wheres['pid'] = $first_catalogue;
        } else {
            $wheres['pid'] = ['gt', 0];
        }
        $allList = Db::name('teaching_catalogue')->where($wheres)->column('id');
        if(!empty($id)) {
            $chapter = Db::name('teaching_combo')->where($where)->value('chapter_arr');
            $second = explode(',',trim($chapter,','));
            $allList = array_intersect($second,$allList);
        }
        $all['id'] = ['in', $allList];
        $allData = Db::name('teaching_catalogue')->where($all)->select();
        $combo_id = Db::name('teaching_sale')->where(['school_id'=>$school_id])->value('combo_id');
        $chapters = Db::name('teaching_combo')->where(['id'=>$combo_id])->value('chapter_arr');
        $have = explode(',',trim($chapters,','));
        foreach ($allData as &$v) {
            $tmp = Db::name('prepare_lesson')->where(['catalogue_id'=>$v['id']])->field('cover,description')->find();
            $v['cover'] = $tmp['cover'];
            if(empty($v['cover'])){
                $v['cover'] = 'http://image.zgxyzx.net/default.png';
            }
            $v['description'] = $tmp['description'];
            if(in_array($v['id'],$have)){
                array_push($result['list1'],$v);
            } else {
                array_push($result['list2'],$v);
            }
        }
        return $result;
    }
    public function delComboData($where)
    {
        $data = Db::name('teaching_sale')->where($where)->select();
        if (empty($data)) {
            $wheres['id'] = $where['combo_id'];
            Db::name('teaching_combo')->where($wheres)->delete();
            return 1;
        } else {
            return -1;
        }
    }
    public function getComboName($where)
    {
        $description = Db::name('teaching_combo')->where($where)->value('description');
        return $description;
    }
    public function getCatalogueList($id){
        if($id==0){
            $first = Db::name('teaching_catalogue')->where(['pid'=>0])->field('id,name')->select();
        }else{
            $first = [];
            $chapter = Db::name('teaching_combo')->where(['id'=>$id])->value('chapter_arr');
            if(!empty($chapter)){
                $second = explode(',',trim($chapter,','));
                foreach ($second as $value) {
                    $pid = Db::name('teaching_catalogue')->where(['id'=>$value])->value('pid');
                    if(!in_array($pid, $first)){
                        array_push($first,$pid);
                    }
                }
            }
            $where['id'] = ['in', $first];
            $first = Db::name('teaching_catalogue')->where($where)->field('id,name')->select();
        }
        return $first;
    }

}
