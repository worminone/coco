<?php

namespace app\article\model;

use think\Db;

class Article
{
    //ID 获取 文章信息
    public function getArticleInfo($id, $term_type = 1)
    {
        $author = new Author;
        $article = new Article;

        // $termArticleInfo = db('term_article')->find($id);
        // $id = $termArticleInfo['article_id'];

        $info = Db::name('Article')
                ->where(['id'=>$id])
                ->find();
        $author_info    = $author->getAuthorInfo($info['author_id']);
        $term_article   = $article->getTermArticleImg($info['id'],$term_type);
        $info['img']    = $term_article['head_img'];
        $info['publish_time'] = $term_article['publish_time'];
        $info['avatar'] = $author_info['avatar'];
        $info['username'] = $author_info['username'];
        // $info['cover'] = $termArticleInfo['cover'];
        // $info['term_type_title'] = db('term_type')->where('id='.$termArticleInfo['term_type'])->value('name');
        $tag_map = Db::name('TagMap')->where(['post_id'=>$id, 'type'=>1])->column('tag_id');
        $tag = Db::name('Tags')->column('name','id');
        $tags = '';
        for ($i=0; $i < count($tag_map); $i++) {
            $tags[] = $tag[$tag_map[$i]];
        }
        if($tags !='') {
            $info['tags'] = implode(',', $tags);
        } else {
            $info['tags'] = '';
        }
        return $info;
    }

    //后去专业ID
    public function getArticleForTermId($id)
    {
        $author = new Author;
        $article = new Article;

        $termArticleInfo = db('term_article')->find($id);
        $id = $termArticleInfo['article_id'];

        $info = Db::name('Article')
                ->where(['id'=>$id])
                ->find();
        $author_info    = $author->getAuthorInfo($info['author_id']);
        $term_article   = $article->getTermArticleImg($info['id'],$termArticleInfo['term_type']);
        $info['img']    = $term_article['head_img'];
        $info['publish_time'] = $term_article['publish_time'];
        $info['avatar'] = $author_info['avatar'];
        $info['username'] = $author_info['username'];
        $info['cover'] = $termArticleInfo['cover'];
        $info['term_type'] = $termArticleInfo['term_type'];

        if ($termArticleInfo['term_type']) {
            $info['term_type_title'] = db('term_type')->where('id='.$termArticleInfo['term_type'])->value('name');
        }

        $tag_map = Db::name('TagMap')->where(['post_id'=>$id, 'type'=>$termArticleInfo['term_type']])->column('tag_id');
        $tag = Db::name('Tags')->column('name','id');
        $tags = '';
        for ($i=0; $i < count($tag_map); $i++) {
            $tags[] = $tag[$tag_map[$i]];
        }
        if($tags !='') {
            $info['tags'] = implode(',', $tags);
        } else {
            $info['tags'] = '';
        }
        return $info;
    }



    //标题 获取 文章ID
    public function getArticleFromTitle($where)
    {
        return Db::name('Article')
            ->where($where)
            ->column('id');
    }


    public function addViewCount($id)
    {
       return Db::name('Article')->where(['id'=>$id])->setInc('view_count', 1);
    }

    //获取关联多的标签
    public function getTagList()
    {
        $info['names'] = Db::name('Tags')->order('count desc')->limit(10)->column('name');
        return $info;

    }

    //添加到关键词中

    public function setTagCount($id, $names)
    {
        $name = explode(',', $names);
        $tags_name = '';
        foreach ($name as $key => $value) {
            $info = Db::name('Tags')->where(['name'=>$value])->find();
            if(empty($info)) {
                Db::name('Tags')->insert(['name'=>$value]);
                $tid = Db::name('Tags')->getLastInsID();
                $data = ['post_id'=>$id,'tag_id'=>$tid];
            } else {
                Db::name('Tags')->where(['name'=>$value])->setInc('count');
                $data = ['post_id'=>$id,'tag_id'=>$info['id']];
            }
            $tags_name[] = $data;
        }
        Db::name('TagMap')->where(['post_id'=>$id])->delete();
        return  Db::name('TagMap')->insertAll($tags_name);
    }

    //验证辩题唯一性
    public function verifyArticleName($title)
    {
        return Db::name('Article')->where(['title'=>$title])->find();
    }


    //根据article_id 获取内容列表图片
    public function getTermArticleImg($id, $term_type){
       return  Db::name("TermArticle")
            ->field('cover,head_img,size,publish_time')
            ->where(['article_id'=>$id, 'term_type'=>$term_type])
            ->find();
    }
}
