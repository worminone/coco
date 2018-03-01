<?php

namespace app\article\model;

use think\Db;
use app\ad\model\Ad;
use app\common\model\Statistics;

class Topic
{
    public $topic_type = [
                '1'=>'普通专题',
                '2'=>'专业专题',
                '3'=>'大学专题'
            ];
    //ID 获取 咨询信息
    public function getTopicInfo($id)
    {
        $term = new Term();
        $info = DB::name('Topic')
            ->where(['id'=>$id])
            ->find();
        $topic_type = $this->getTopicType();
        $term_type = $term->getTermTypeColumnInfo();
        $info['term_name'] = $term_type[$info['term_type']];
        $info['topic_type_name'] = $topic_type[$info['topic_type']];

        return $info;
    }

    //获取专题类别信息
    public function getTopicType()
    {
        return DB::name('TopicType')->column('name', 'id');
    }

    //获取专题类别列表
    public function getTopicTypeList()
    {
        return DB::name('TopicType')->select();
    }

    //暂时只能用在一体机
    public function getTopicArticleInfo($id,$province_id,$limit)
    {
        $term = new Term();
        $article = new Article();
        $author = new Author();
        $ad = new Ad();
        //判断时候是广告
        $obj_values = $ad->getAdPostId($province_id);
        $obj_where['id'] = ['in', $obj_values];
        $obj_where['term_type'] = 1;
        $article_id = DB::name('TermArticle')
            ->where($obj_where)
            ->column('article_id');

        $prefix = config('database.prefix');
        $t_info = DB::name('Topic')
            ->field('id,term_type,title,description,head_img,recommend')
            ->where(['id'=>$id])
            ->find();
      
        $term_info = $term->getTermType($t_info['term_type']);
        $t_info['term_name'] = $term_info['name'];
        $term_article_ids = DB::name('ArticleTopic')
            ->where(['topic_id'=>$id])
            ->column('term_article_id');

        $ids = Db::table($prefix.'term_article')
            // ->field('a.id aid , a.*, at.* ')
            ->alias('at')
            ->join($prefix.'article a','at.article_id=a.id', 'right')
            ->where(['at.status'=>1, 'at.term_type'=>1])
            ->where('at.publish_time', '<=', time())
            ->order(['at.is_top'=>'desc', 'a.sort'=>'desc', 'at.publish_time'=>'desc'])
            ->column('a.id');
        $ids = implode(',', $ids);
   		$info = DB::name('ArticleTopic')
            ->where(['topic_id'=>$id])
            ->where('term_article_id','in',$ids)
            ->limit($limit)
            ->order("Field (term_article_id,$ids)")
            ->select();

        foreach ($info as $key => $value) {
            $a_info = DB::name('Article')
                ->where(['id'=>$value['term_article_id']])
                ->find();
            if(in_array($value['term_article_id'], $article_id)) {
                $infos = DB::name('TermArticle')->where(['article_id'=>$value['term_article_id'],'term_type'=>1])->find();
                $ad_info = DB::name('Ad')->where(['post_id'=>$infos['id']])->find();
                $info[$key]['is_ad'] = $ad_info['id'];
                //统计曝光率
                $one['show_type'] = $ad_info['id'];
                $one['ad_id'] = $ad_info['id'];
                $one['province_id'] = $province_id;
                $one['school_id'] = input('param.school_id', 0);
                $Statistics = new Statistics();
                $Statistics->adShowStatistics($one);
            } else {
                $info[$key]['is_ad'] = 0;
            }
            $term_article = $article->getTermArticleImg($value['term_article_id'],1);
            $author_info = $author->getAuthorInfo($a_info['author_id']);
            $info[$key]['avatar'] = $author_info['avatar'];
            $info[$key]['article_id'] = $value['term_article_id'];
            $info[$key]['username'] = $author_info['username'];
            $info[$key]['title'] = $a_info['title'];
            $info[$key]['img'] = $term_article['cover'];
            $info[$key]['size'] = $term_article['size'];
            $info[$key]['content']  = mb_substr(strip_tags($a_info['content']),0,50, 'utf8');
            // $time = strtotime($a_info['create_time']);
            $info[$key]['publish_time'] = wordTime($term_article['publish_time']);
        }
        $t_info['article'] = $info;
        return $t_info;
    }

    //获取专题信息
    public function getTopicList($id)
    {
        return DB::name('Topic')
            ->where(['id'=>$id])
            ->find();
    }

    //专题下的文章信息
    public function getTopicArticleList($topic_id)
    {
        $list = DB::name("ArticleTopic")
            ->where(['topic_id'=>$topic_id])
            ->select();

        foreach ($list as $key => $value) {
            $info = model('Article')->getArticleInfo($value['term_article_id']);
            if(!isset($info['id'])) {
                unset($list[$key]);
                continue;
            }
            $list[$key]['title'] = $info['title'];
        }
        return $list;
    }

    //前端获取专题信息列表
    public function getTopicCoverList($where, $field, $limit, $order)
    {
        return DB::name('Topic')
                ->field($field)
                ->where($where)
                ->limit($limit)
                ->order($order)
                ->select();
    }
}
