<?php

namespace app\article\model;

use think\Db;
use app\ad\model\Ad;
use app\common\model\Statistics;

class Term
{
    //ID 获取 文章内容信息
    public function getTermInfo($aid)
    {
        $list = DB::name('TermArticle')
            ->where(['article_id'=>$aid, 'status'=> 1])
            ->select();
        return $list;
    }

    public function getTermType($id)
    {
        return DB::name('TermType')
            ->where(['id'=>$id])
            ->find();
    }

    public function getTermInfoById($id)
    {
        return DB::name('TermArticle')
            ->where(['id'=>$id])
            ->find();
    }

    public function getTermTypeInfo($where)
    {
        return DB::name('TermType')->where($where)->select();
    }

    public function getTermTypeColumnInfo()
    {
        return DB::name('TermType')->column('name', 'id');
    }

    //article_id temtype 去重复
    public function getUniqueTerm($article_id, $term_type)
    {
        return DB::name('TermArticle')
            ->where(['article_id'=>$article_id, 'term_type'=>$term_type])
            ->find();
    }

    //三端的数据列表文章内容[暂时只能用在一体机]
    public function getTermList($where, $field, $limit, $order, $province_id)
    {
        $author = new Author;
        $article = new Article;
        $ad = new Ad();
        //判断时候是广告
        $obj_values = $ad->getAdPostId($province_id);
        $obj_where['id'] = ['in', $obj_values];
        $obj_where['term_type'] = 1;
        $article_id = DB::name('TermArticle')
            ->where($obj_where)
            ->column('article_id');
        $prefix = config('database.prefix');

        $term_article = Db::table($prefix.'term_article')
            ->field($field)
            ->alias('at')
            ->join($prefix.'article a','at.article_id=a.id', 'right')
            ->where($where)
            ->order(['at.is_top'=>'desc', 'a.sort'=>'desc', 'at.publish_time'=>'desc'])
            ->limit($limit)
            ->select();
        foreach ($term_article as $key => $value) {
            $a_info = DB::name('Article')
                ->where(['id'=>$value['article_id']])
                ->find();
            //此处缺失笔名信息和转专业大学有信息
            $author_info = $author->getAuthorInfo($a_info['author_id']);
            $term_article_info = $article->getTermArticleImg($a_info['id'],1);
            $term_article[$key]['term_article_id'] = $value['article_id'];
            $term_article[$key]['avatar'] = $author_info['avatar'];
            $term_article[$key]['username'] = $author_info['username'];
            $term_article[$key]['title'] = $a_info['title'];
            $term_article[$key]['img'] = $term_article_info['cover'];
            $term_article[$key]['size'] = $term_article_info['size'];
            if($a_info['content'] !='') {
                $term_article[$key]['content'] = mb_substr(strip_tags($a_info['content']),0,50,'utf-8');
            }
            if(in_array($value['article_id'], $article_id)) {
                $info = DB::name('TermArticle')->where(['article_id'=>$value['article_id'],'term_type'=>1])->find();
                $ad_info = DB::name('Ad')->where(['post_id'=>$info['id']])->find();
                $term_article[$key]['is_ad'] = $ad_info['id'];
                //统计曝光率
                $one['show_type'] = $ad_info['id'];
                $one['ad_id'] = $ad_info['id'];
                $one['province_id'] = $province_id;
                $one['school_id'] = input('param.school_id', 0);
                $Statistics = new Statistics();
                $Statistics->adShowStatistics($one);
            } else {
                $term_article[$key]['is_ad'] = 0;
            }
            // $time = strtotime($a_info['create_time']);
            $term_article[$key]['publish_time'] = wordTime($value['publish_time']);
            // $term_article[$key]['publish_time'] = wordTime($time);
        }
        return $term_article;
    }

}
