<?php
namespace app\article\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Article extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /article/Article/articleList 查看文章列表
     * @apiVersion              1.0.0
     * @apiName                 articleList
     * @apiGROUP                Article
     * @apiDescription          查看文章列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       title 标题名称（可选）
     * @apiParam {String}       pagesize 分页数
     * @apiParam {int}          status   状态（1：正常，0：禁用）可选
     * @apiParam {int}          type  判断完整文章(1 ：完整)可选
     * @apiParam {Int}          page 页码（可选），默认为1
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id:  文章ID,
     *      title: 文章标题,
     *      author_name: 编辑笔名,
     *      create_time: 发布日期,
     *      status: 状态（1：正常，0：禁用）
     *      publish_time: 预发布日期
     * }
     * ]
     * }
     *
     */
    public function articleList()
    {
        $title = input('param.title');
        $status = input('param.status', '-1', 'int');
        $type = input('param.type', '', 'int');
        $term_type = input('param.term_type', '1', 'int');
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        if($status >= 0) {
            $where['status'] = $status;
        }
        if(!empty($title)) {
            $where['title'] = ['like', "%".$title."%"];
        }
        if($type == 1) {
           $s_where['term_type'] = $term_type;
           $s_where['status'] = 1;
           $article_ids = DB::name('TermArticle')->where($s_where)->column('article_id');
           $where['id'] = ['in', $article_ids];
        }
        $field = 'id,title,author_id,create_time,status,'.
        'FROM_UNIXTIME(publish_time,"%Y-%m-%d %H:%i:%S") as publish_time';
        $list = $this->getPageList('Article', $where, 'id desc', $field, $pagesize);

        foreach ($list['list'] as $key => $value) {
            $author_name = DB::name('UserAuthor')->where(['id'=>$value['author_id']])->column('name');
            if($author_name) {
                $author_name = $author_name[0];
            } else {
                $author_name = '匿名';
            }
            if('1970-01-01 08:00:00' == $value['publish_time']) {
                $list['list'][$key]['publish_time'] = '';
            }
            $list['list'][$key]['author_name'] = $author_name;
            
        }
        // dd($list);
        if ($list['count'] > 0) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /article/Article/addArticle 添加文章
     * @apiVersion              1.0.0
     * @apiName                 addArticle
     * @apiGROUP                Article
     * @apiDescription          添加文章
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          uid   登录用户ID
     * @apiParam {Int}          author_id   作者笔名id
     * @apiParam {String}       title   文章标题
     * @apiParam {String}       summary   文章摘要
     * @apiParam {String}       tags   文章标签(多个用','分割)
     * @apiParam {String}       content   文章内容
     * @apiParam {String}       publish_time   预发布时间
     * @apiParam {Int}          view_count   访问数
     * @apiParam {Int}          zan_count   点赞数
     * @apiParam {Int}          sort   排序
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function addArticle()
    {
        
        $info = input('param.');
        $info['content'] = input('param.content', '' ,'htmlspecialchars_decode');
        $res = model('Article')->verifyArticleName($info['title']);
        if($res) {
            $this->response('-1', '标题已存在');
        }
        DB::name('Article')->insert($info);
        $id = Db::name('Article')->getLastInsID();
        $res = model('Article')->setTagCount($id, $info['tags']);
        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

    /**
     * @api {post} /article/Article/editArticle 编辑查看文章
     * @apiVersion              1.0.0
     * @apiName                 editArticle
     * @apiGROUP                Article
     * @apiDescription          编辑查看文章
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id   文章ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *       author_id : 作者笔名id
     *       title : 文章标题
     *       summary : 文章摘要
     *       content : 文章内容
     *       tags : 文章标签
     *       img : 图片地址
     *       create_time : 发布时间
     *       publish_time : 预发布时间
     *       view_count : 访问数
     *       zan_count : 点赞数
     *       sort   排序
     * }
     * ]
     * }
     *
     */
    public function editArticle()
    {
        $id = input('param.id');
        $info = model('Article')->getArticleInfo($id);
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据');
        }
    }


    /**
     * @api {post} /article/Article/saveArticle 提交修改文章
     * @apiVersion              1.0.0
     * @apiName                 saveArticle
     * @apiGROUP                Article
     * @apiDescription          提交修改文章
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id   文章ID
     * @apiParam {Int}          author_id   作者笔名id
     * @apiParam {String}       title   文章标题
     * @apiParam {String}       summary   文章摘要
     * @apiParam {String}       tags   文章标签(多个用','分割)
     * @apiParam {String}       content   文章内容
     * @apiParam {String}       publish_time   预发布时间
     * @apiParam {Int}          view_count   访问数
     * @apiParam {Int}          zan_count   点赞数
     * @apiParam {Int}          sort   排序
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function saveArticle()
    {
        $info = input('param.');
        $info['content'] = htmlspecialchars_decode($info['content']);
        DB::name('Article')->update($info);
        $res = model('Article')->setTagCount($info['id'], $info['tags']);
        if ($res !==false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /article/Article/deleteArticle 设置文章状态
     * @apiVersion              1.0.0
     * @apiName                 deleteArticle
     * @apiGROUP                Article
     * @apiDescription          设置文章状态
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id   文章ID
     * @apiParam {Int}          status   状态（1：正常，0：禁用）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function deleteArticle()
    {
        $id = input("param.id");
        $status = input("param.status", '1','intval');
        if (empty($id)) {
            $this->response('-1', '数据不能为空');
        }
        $res = DB::name('Article')
            ->where('id', 'in', $id)
            ->update(['status'=>$status]);
        if ($res !==false) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }
    /**
     * @api {post} /article/Article/tagList 获取标签
     * @apiVersion              1.0.0
     * @apiName                 tagList
     * @apiGROUP                Article
     * @apiDescription          获取标签
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *       names : 标签
     * }
     * ]
     * }
     *
     */
    public function tagList()
    {
        $list = model('Article')->getTagList();
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

}
