<?php

namespace app\api\controller\article;

use think\Db;
use app\common\controller\Api;
use app\article\model\Article;

class ArticleManage extends Api
{
    /**
     * @api {post} /Api/article.ArticleManage/getArticleList 相关文章列表
     * @apiVersion 1.0.0
     * @apiName getArticleList
     * @apiGroup article
     * @apiDescription 相关文章列表
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {Int} college_id 大学ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     *  code: "1",
     *  msg: "操作成功",
     *  data: [
     *  {
     *      term_article_id: 文章id
     *      title:           标题
     *      cover:           封面
     *      content:         内容
     *  }
     *  ]
     *  }
     */
    public function getArticleList()
    {
        $category_id = input('category_id'); 
        $where['category_id'] = ['in', $category_id]; 
        $where['dd_article.status'] = 1;
        $where['dd_term_article.status'] = 1;
        $where['dd_term_article.publish_time'] = ['<=', time()];
        $page = input('param.page', 1, 'int');
        $e = input('param.pagesize', 2, 'int');
        $limit = (($page -1) * $e) . ',' . $e;
        $orderd = 'is_top desc, sort desc,dd_term_article.publish_time desc';

        $e_list = Db::name('TermArticle')
        ->join('dd_article','dd_article.id = dd_term_article.Article_id')
        ->join('dd_user_author','dd_user_author.id = dd_article.author_id','LEFT')
        ->where($where)
        ->field('*,dd_term_article.publish_time pt,(case when dd_user_author.name is null then \'匿名\' else dd_user_author.name end) author_name')
        ->limit($limit)
        ->order($orderd)
        ->select();
//var_dump($e_list);
        // $article_ids = DB::name('TermArticle')->where($where)->limit($limit)->column('article_id');
        // $article = DB::name('TermArticle')->where($where)->limit($limit)->select();
        // $swhere['id'] = ['in',$article_ids];
        // $s_list = DB::name('Article')->where($swhere)->limit($limit)->order($orderd)->select();
        // $e_list = [];
        //var_dump($s_list);
        foreach ($e_list as $key => $v) {
           $e_list[$key]['id'] = $v['article_id'];
           $e_list[$key]['title'] = $v['title'];
           $e_list[$key]['zan_count'] = $v['zan_count'];
           $e_list[$key]['content'] = $v['content'];
           $e_list[$key]['cover'] = $v['cover'];
           $e_list[$key]['note'] = 0;
           $e_list[$key]['publish_time'] = wordTime($v['pt']);
           $e_list[$key]['publish_time_name'] = date('Y-m-d', $v['pt']);
        }
        if ($e_list) {
            $this->response('1', '操作成功', $e_list);
        } else {
            $this->response('1', '暂无数据');
        } 
    }

/**
     * @api {post} /Api/article.ArticleManage/getArticleListCount 相关文章列表总条数
     * @apiVersion 1.0.0
     * @apiName getArticleListCount
     * @apiGroup article
     * @apiDescription 匹配相关文章列表
     *
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     *  code: "1",
     *  msg: "操作成功",
     *  data: 23
     */
    public function getArticleListCount()
    {
        $category_id = input('category_id'); 
        $where['category_id'] = ['in', $category_id]; 
        $where['dd_article.status'] = 1;
        $where['dd_term_article.status'] = 1;
        $where['dd_term_article.publish_time'] = ['<=', time()];
        $page = input('param.page', 1, 'int');
        $e = input('param.pagesize', 2, 'int');
        $orderd = 'is_top desc, sort desc,dd_article.publish_time desc';

        $e_list = Db::name('TermArticle')
        ->join('dd_article','dd_article.id = dd_term_article.Article_id')
        ->where($where)
        ->field('*,dd_term_article.publish_time pt')
        ->order($orderd)
        ->count();
        if ($e_list) {
            $this->response('1', '操作成功', $e_list);
        } else {
            $this->response('1', '暂无数据');
        }
        
        
    }
    /**
     * @api {post} /Api/article.ArticleManage/articleInfo 文章详情
     * @apiVersion              1.0.0
     * @apiName                 articleInfo
     * @apiGROUP                APi
     * @apiDescription          文章详情
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id   文章id
     * @apiParam {String}       term_type   文章类型
     * @apiParam {String}       count   有传文章阅读数+1
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id: 1,
     *   title: 标题名称,
     *   img: 文章图片,
     *   size: 图片尺寸,
     *   content: 文章内容(调用此字段即可),
     *   publish_time: 发布时间,
     *   view_count: 阅读数,
     *   username: 笔名,
     *   avatar: 用户图标,
     *   tags:标签
     * }
     *
     */
    public function articleInfo()
    {
        $id = input('param.id', '', 'int');
        $term_type = input('param.term_type', 2, 'int');
        $count = input('param.count', '0', 'int');
        $where['dd_term_article.article_id'] = $id;
        $where['dd_term_article.term_type'] = $term_type;
        $where['dd_article.id'] = $id;
        $where['dd_term_article.status'] = 1;
        $order = 'sort desc,dd_term_article.publish_time desc';
        $article = Db::name('TermArticle')
            ->join('dd_article','dd_article.id=dd_term_article.article_id')
            ->field('
            dd_article.id,
            dd_article.title,
            dd_article.zan_count,
            dd_article.view_count,
            dd_article.content,
            dd_term_article.head_img,
            dd_term_article.cover,
            dd_article.author_id,
            dd_term_article.publish_time
            ')
            ->where($where)
            ->order($order)
            ->select();
//        print_r( Db::name('TermArticle')->getLastSql());
//        print_r($article);
        $author_list = Db::name('userAuthor')->column('id,name');
        $e_list = [];
        foreach ($article as $key => $v) {
           $e_list[$key]['id'] = $v['id'];
           $e_list[$key]['title'] = $v['title'];
           $e_list[$key]['zan_count'] = $v['zan_count'];
           $e_list[$key]['view_count'] = $v['view_count'];
           $e_list[$key]['content'] = $v['content'];
           $e_list[$key]['cover'] = $v['cover'];
           $e_list[$key]['head_img'] = $v['head_img'];
           $e_list[$key]['author_name'] = empty($author_list[$v['author_id']])?'匿名':$author_list[$v['author_id']];
           $e_list[$key]['note'] = 0;
           $e_list[$key]['publish_time_name'] = date('Y-m-d',$v['publish_time']);
           $e_list[$key]['publish_time'] = wordTime($v['publish_time']);
        }
        if($count)
            Db::name('Article')->where(['id'=>$id])->setInc('view_count', 1);
        if ($e_list) {
            $this->response('1', '操作成功', $e_list);
        } else {
            $this->response('1', '暂无数据');
        }
    }

    public function readArticle()
    {
        $id = input('param.id', '');
        $swhere['id'] = ['in', $id];
        $where['status'] = 1; 
        $orderd = 'sort desc,publish_time desc';
        $s_list = DB::name('Article')->where($swhere)->order($orderd)->column('title');
        if ($s_list) {
            $this->response('1', '操作成功', $s_list);
        } else {
            $this->response('1', '暂无数据');
        }
    }
}
