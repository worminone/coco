<?php

namespace app\article\model;

use think\Db;

class Category
{
    //ID 获取 顶级分类列表
    public function getCategoryOneInfo($where)
    {
        return DB::name('ArticleCategory')
            ->field('id,name')
            ->where($where)
            ->select();
    }


    //ID 获取 当前分类信息和上级分类信息
    public function getCategoryInfo($id)
    {
        $info = DB::name('ArticleCategory')
            ->where(['id'=>$id, 'status'=>1])
            ->find();
        if($info['pid'] != 0) {
            $top_info = DB::name('ArticleCategory')
                ->where(['id'=>$info['pid'], 'status'=>1])
                ->find();
            $info['catrgory_top_name'] = $top_info['name'];
            $info['catrgory_top_id'] = $top_info['id'];
        } else {
            $info['catrgory_top_name'] = $info['name'];
            $info['catrgory_top_id'] = $info['id'];
            $info['name'] = '';
        }
        return $info;
            
    }

    //获取分类列表
    public function getCategoryList($where)
    {
        $count =  DB::name('ArticleCategory')
            ->where($where)
            ->count();
        $list =  DB::name('ArticleCategory')
            ->field('id,name,icon,sort,status')
            ->where($where)
            ->select();
        
        foreach ($list as $key => $value) {
            $p_list = DB::name('ArticleCategory')
                ->field('id,name,icon,sort,status')
                ->where(['pid'=>$value['id'], 'status'=>1])
                ->select();
            $list[$key]['id'] = (string)$value['id'];
            foreach ($p_list as $k => $v) {
                $p_list[$k]['id'] = (string)$v['id'];
            }
            $list[$key]['catrgory'] = $p_list;

        }
        if (!empty($list)) {
            $data = [
                'list'    => $list
            ];
        } else {
            $data = [
                'list'    => []
            ];
        }
        return $data;
    }

    //获取类型分类列表
    public function getCategoryTermList($where, $field)
    {
        return DB::name('ArticleCategory')
            ->field($field)
            ->where($where)
            ->order('sort desc')
            ->select();
    }

    //获取类型分类id
    public function getCategoryTermIds($where)
    {
        return DB::name('ArticleCategory')
            ->where($where)
            ->column('id');
    }

    //删除检查内容是否存在
    public function contentExisit($id)
    {
        $v_info = DB::name('Video')->where(['category_id'=>$id])->find();
        $t_info = DB::name('TermArticle')->where(['category_id'=>$id])->find();
        $a_info = DB::name('ArticleCategory')->where(['pid'=>$id])->find();
        $j_info = DB::name('Journal')->where(['category_id'=>$id])->find();
        $s_info = DB::name('SlideShow')->where(['category_id'=>$id])->find();
        
        if($v_info || $t_info || $a_info || $j_info || $s_info) {
            return 1;
        }
    }

    //检查同一名字是否存在
    public function checkTitleExisit($name,$pid,$term_type,$id)
    {   $where = '';
        if(!empty($id)) {
            $where['id'] = ['not in', $id];
        }
        return $info = DB::name('ArticleCategory')
            ->where(['name'=>$name, 'pid'=>$pid, 'term_type'=>$term_type, 'status'=>1])
            ->where($where)
            ->find();
    }
}