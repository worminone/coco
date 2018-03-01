<?php

namespace app\article\model;

use think\Db;

class Video
{
    //ID 获取 文章信息
    public function getVideoInfo($id)
    {
        $info = DB::name('Video')
            ->where(['id'=>$id])
            ->find();
        $info['category_id'] = (string)$info['category_id'];
        $info['term_type'] = (string)$info['term_type'];
        $tag_map = DB::name('TagMap')->where(['post_id'=>$id, 'type'=>2])->column('tag_id');
        $tag = DB::name('Tags')->column('name','id');
        $tags = '';
        for ($i=0; $i < count($tag_map); $i++) { 
            $tags[] = $tag[$tag_map[$i]];
        }
        if($tags != '') {
            $info['tags'] = implode(',', $tags);
        } else {
            $info['tags'] = '';
        }
        return $info;
    }

    //添加到关键词中
    public function setTagCount($id, $names)
    {
        
        $name = explode(',', $names);
        $tags_name = '';
        foreach ($name as $key => $value) {
            $info = DB::name('Tags')->where(['name'=>$value])->find();
            if(empty($info)) {
                DB::name('Tags')->insert(['name'=>$value]);
                $tid = Db::name('Tags')->getLastInsID();
                $data = ['post_id'=>$id,'tag_id'=>$tid,'type'=>2];
            } else {
                DB::name('Tags')->where(['name'=>$value])->setInc('count');
                $data = ['post_id'=>$id,'tag_id'=>$info['id'],'type'=>2];
            }
            $tags_name[] = $data;
        }
        DB::name('TagMap')->where(['post_id'=>$id])->delete();
        return  DB::name('TagMap')->insertAll($tags_name);
    }

    //公用接口 获取视频列表
    public function getVideoList($where, $field, $limit, $order)
    {
       return  DB::name('Video')
                ->field($field)
                ->where($where)
                ->limit($limit)
                ->order($order)
                ->select();
    }

    //一体机专业职业院校视频审核
    public function getVideoCollegeList($type, $id, $limit, $order, $s_where)
    {
        $term_type = $s_where['term_type'];
        $field = 'id,title,play_type,content,cover,description';
        $where['college_type'] = $type;
        $s_term_type = $term_type;
        $term_type = ['0',$term_type];
        $where['term_type'] = ['in',$term_type];
        $where['status'] = 1;

        if ($type == 2) {
//            $admin_key = config('admin_key');
//            $college_api = config('college_api');
//            $url =  $college_api.'/index/Major/majorInfo';
//            $param['type_id'] = $id;
//            $param['admin_key'] = $admin_key;
//            $data = curl_api($url, $param, 'post');
////            dd($data);
//            if($data['code'] == -1) {
//                $where['college_id'] = ['<', 0];
//            } else {
//                //字段改为type_id 不应majorNumber 存储
////                $where['college_id'] = $id;
//                $where['college_id'] = $data['data']['majorNumber'];
//            }
            if(empty($s_where['c_id'])) {
                $term_type = $s_term_type;
            }
            $where['college_id'] = $id;
        } elseif($type == 3) {
            $admin_key = config('admin_key');
            $college_api = config('college_api');
            $url =  $college_api.'/index/Occupation/editOccupation';
            $param['occupation_id'] = $id;
            $param['admin_key'] = $admin_key;
            $data = curl_api($url, $param, 'post');
            if($data['code'] == -1) {
                $where['college_id'] = ['<', 0];
            } else {
                $where['college_id'] = $id;
            }
            
        } else {
            $where['college_id'] = $id;
        }
        $where['term_type'] = ['in',$term_type];
//        dd($where);
        return $this->getVideoList($where, $field, $limit, $order);
    }

}
