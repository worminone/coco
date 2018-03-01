<?php

namespace app\lessonplan\model;

use think\Db;

class Menu
{
    public function getMenuListByName($where){
        $where['pid']=0;
        $first=Db::name('teaching_catalogue')->field('id,pid,name as label')->where($where)->select();
        $where['pid']=['>', 0];
        $second=Db::name('teaching_catalogue')->field('id,pid,name as label')->where($where)->select();
        foreach ($first as $num=> $val) {
            $first[$num]['children']=array();
        }
        foreach ($second as $key=> $arr) {
            $num=-1;
            $pid=$arr['pid'];
            $count=count($first);
            if ($count>0) {
                for ($i=0; $i<$count; $i++) {
                    if($pid==$first[$i]['id']) {
                        $num=$i;
                    }
                }
                if($num>=0){
                    array_push($first[$num]['children'],$arr);
                } else {
                    $tmp=Db::name('teaching_catalogue')->field('id,pid,name as label')->where("id='$pid'")->find();
                    $tmp['children']=array();
                    array_push($tmp['children'],$arr);
                    array_push($first,$tmp);
                }
            } else {
                $first=Db::name('teaching_catalogue')->field('id,pid,name as label')->where("id='$pid'")->select();
                $first[0]['children']=array();
                array_push($first[0]['children'], $arr);
            }
        }
        return $first;
    }

    public function getMenuList(){
        $first=Db::name('teaching_catalogue')->field('id,pid,name as label')->where('pid=0')->select();
        foreach ($first as $key=>$arr) {
            $pid=$arr['id'];
            $first[$key]['children']=Db::name('teaching_catalogue')->field('id,pid,name as label')
                ->where("pid='$pid'")->select();
            foreach ($first[$key]['children'] as $k=>$val){
                $first[$key]['children'][$k]['flag']=false;
            }
            $first[$key]['count']=count($first[$key]['children']);
        }

        return $first;
    }

    public function delMenu($id)
    {
        $where['catalogue_id']=$id;
        $tmp=Db::name('teaching_content')->where($where)->select();
        if (!empty($tmp)) {
            return -1;
        }
        $where3['chapter_arr']=['like', ',%'.$id.'%,'];
        $tmp3=Db::name('teaching_combo')->where($where3)->select();
        if (!empty($tmp3)) {
            return -3;
        }
        $tmp4=Db::name('teaching_catalogue')->where("pid='$id'")->select();
        if (!empty($tmp4)) {
            return -4;
        }
        Db::name('teaching_catalogue')->where("id='$id'")->delete();
        Db::name('prepare_lesson')->where("catalogue_id='$id'")->delete();
        return 1;
    }
    public function getTeachMenu($chapter){
        $str=trim($chapter,',');
        $where['id']=['in', explode(',',$str)];
        $where['pid']=0;
        $first=Db::name('teaching_catalogue')->field('id,pid,name as label')->where($where)->select();
        foreach ($first as $key=>$arr) {
            $pid=$arr['id'];
            $first[$key]['children']=Db::name('teaching_catalogue')->alias('a')
                ->join('dd_prepare_lesson b', 'b.catalogue_id=a.id')
                ->field('a.id,a.pid,a.name as label,b.cover')
                ->where("pid='$pid'")->select();
        }
        return $first;
    }
    public function getDetail($ids)
    {
        $where['id']=['in',$ids];
        $where['pid']=0;
        $first = Db::name('teaching_catalogue')->field('id,name as label')->where($where)->select();
        foreach ($first as $key=> $val) {
            $pid=$val['id'];
            $first[$key]['children'] = Db::name('teaching_catalogue')->alias('a')
                ->join('dd_prepare_lesson b', 'b.catalogue_id=a.id')
                ->field('a.id,a.pid,a.name as label,b.cover')
                ->where("pid='$pid'")->select();
        }
        $num=count($ids);
        $count=count($first);
        $tmp=array();
        foreach ($ids as $k=>$arr) {
            $pid=Db::name('teaching_catalogue')->where("id='$arr'")->value('pid');
            if(!(in_array($pid,$ids)||$pid==0)){
                $tmp[$count]=Db::name('teaching_catalogue')->field('id,pid,name as label')->where("id='$pid'")->find();
                $wheres['pid']=$pid;
                $wheres['a.id']=$where['id'];
                $tmp[$count]['children']=Db::name('teaching_catalogue')->alias('a')
                    ->join('dd_prepare_lesson b', 'b.catalogue_id=a.id')
                    ->field('a.id,a.pid,a.name as label,b.cover')
                    ->where($wheres)->select();
                $children_count=count($tmp[$count]['children']);
                for ($i=0; $i<$children_count; $i++){
                    if(empty($tmp[$count]['children'][$i]['cover'])){
                        $tmp[$count]['children'][$i]['cover']='http://image.zgxyzx.net/default.png';
                    }
                }
                $ids[$num++]=$pid;
                $count++;
            }
        }
        $first=array_merge($first,$tmp);
        array_multisort(array_column($first,'id'),SORT_ASC,$first);
        return $first;
    }

    public function getTeachingCombo_old($school_id, $pid, $title)
    {
        if (empty($pid)) {
            $where['pid'] = ['<>', 0];
        } else {
            $where['pid'] = $pid;
        }
        if (!empty($title)) {
            $where['name'] = ['like', '%'.trim($title).'%'];
        }
        $catalogue = Db::name('teaching_catalogue')->field('id, name')->where($where)->select();
        $ComboSaleId = Db::name('teaching_sale')->where('school_id', $school_id)->value('combo_id');
        $buyCombo = Db::name('teaching_combo')->where('id', $ComboSaleId)->find();
        $chapter_arr = trim($buyCombo['chapter_arr'], ',');
        $chapter_arr = explode( ',', $chapter_arr);
        for ($i=0; $i<count($catalogue); $i++) {
            if (in_array($catalogue[$i]['id'], $chapter_arr)) {
                $catalogue[$i]['is_buy'] = 1;
            } else {
                $catalogue[$i]['is_buy'] = 0;
            }
            $catalogueMain = Db::name('prepare_lesson')->where('catalogue_id', $catalogue[$i]['id'])->find();
            $catalogue[$i]['cover'] = $catalogueMain['cover'];
        }
        return $catalogue;
    }

    public function getTeachingCombo($school_id, $pid, $title)
    {
        if (empty($pid)) {
            $where['pid'] = ['<>', 0];
        } else {
            $where['pid'] = $pid;
        }
        if (!empty($title)) {
            $where['name'] = ['like', '%'.trim($title).'%'];
        }
        $ComboSaleId = Db::name('teaching_sale')->where('school_id', $school_id)->value('combo_id');
        $buyCombo = Db::name('teaching_combo')->where('id', $ComboSaleId)->find();
        $chapter_arr = explode( ',', trim($buyCombo['chapter_arr'], ','));
        $catalogue[] = '';
        $whereList1['id'] = ['in', $chapter_arr];
        $list1 = Db::name('teaching_catalogue')->field('id, name')->where($whereList1)->where($where)
        ->order('pid ASC')->select();
        for ($i=0; $i<count($list1); $i++) {
            $catalogueMain = Db::name('prepare_lesson')->where('catalogue_id', $list1[$i]['id'])->find();
            if (empty($catalogueMain['cover'])) {
                $list1[$i]['cover'] = 'http://image.zgxyzx.net/default.png';
            } else {
                $list1[$i]['cover'] = $catalogueMain['cover'];
            }
            $list1[$i]['description'] = $catalogueMain['description'];
        }
        $whereList2['id'] = ['not in', $chapter_arr];
        $list2 = Db::name('teaching_catalogue')->field('id, name')->where($whereList2)->where($where)
        ->order('pid ASC')->select();
        for ($i=0; $i<count($list2); $i++) {
            $catalogueMain = Db::name('prepare_lesson')->where('catalogue_id', $list2[$i]['id'])->find();
            if (empty($catalogueMain['cover'])) {
                $list2[$i]['cover'] = 'http://image.zgxyzx.net/default.png';
            } else {
                $list2[$i]['cover'] = $catalogueMain['cover'];
            }
            $list2[$i]['description'] = $catalogueMain['description'];
        }

        $list['list1'] = $list1;
        $list['list2'] = $list2;
        return $list;
    }



    public function getTeachingFirstLesson()
    {
        $catalogue = Db::name('teaching_catalogue')->field('id, name')->where('pid', 0)->select();
        return $catalogue;
    }

    public function getLessonMain($id)
    {
        $type = '0,3';
        $where['catalogue_id'] =  $id;
        $where['content_type'] =  ['not in', $type];
        $content = Db::name('teaching_content')->where($where)->select();
        return $content;
    }
    public function getLessonAllMain($id)
    {
        $where['catalogue_id'] =  $id;
        $content = Db::name('teaching_content')->where($where)->select();
        return $content;
    }
}
