<?php

namespace app\api\controller\app;

use think\Db;
use app\common\controller\Api;
use app\article\model\Article;
use app\article\model\Video;

class ArticleManage extends Api
{
    /**
     * @api {post} /Api/app.ArticleManage/getArticleList 相关文章列表
     * @apiVersion 1.0.0
     * @apiName getArticleList
     * @apiGroup Application
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
        $releven_id = input('releven_id', '', 'intval');
        $type = input('type', '', 'intval');
        $page = input('page', '1', 'intval');
        $pagesize = input('pagesize', '10', 'intval');
        $list = DB::name('Topic')->where('config_info!="" ')->select();
        $topic_ids = [];
        foreach ($list as $key => $value) {
            $info = json_decode($value['config_info'],true);
            if ($type ==1) {
                if (isset($info['college_id']) && $info['college_id'] == $releven_id) {
                    $topic_ids[] = $value['id'];
                }
            }  elseif($type == 2) {
                $admin_key = config('admin_key');
                $college_api = config('college_api');
                $url =  $college_api.'/index/Major/majorInfo';
                $param['type_id'] = $releven_id;
                $param['admin_key'] = $admin_key;
                $data = curl_api($url, $param, 'post');
                $majorNumber =  $data['data']['majorNumber'];
                if (isset($info['majorNumber']) && $info['majorNumber'] == $majorNumber) {
                    $topic_ids[] = $value['id'];
                }
            } elseif($type == 3){
                $this->response('1', '暂无数据');
            }
        }
        $article_ids = DB::name('TermArticle')->where(['term_type'=>2])->column('article_id');
        $t_where['topic_id'] = ['in', $topic_ids];
        $t_where['term_article_id'] = ['in', $article_ids];
        $s_list = DB::name('ArticleTopic')->where($t_where)->group('term_article_id')->page($page,$pagesize)->select();
        foreach ($s_list as $key => $value) {
           $a_info = DB::name('Article')->where(['id'=>$value['term_article_id']])->find();
           $ta_info = DB::name('TermArticle')->where(['term_type'=>2,'article_id'=>$value['term_article_id']])->find();
           $s_list[$key]['title'] = $a_info['title'];
           $s_list[$key]['content'] = $a_info['content'];
           $s_list[$key]['cover'] = $ta_info['cover'];
           $s_list[$key]['zan_count'] = $a_info['zan_count'];
           $s_list[$key]['publish_time'] = wordTime($ta_info['publish_time']);
        }
        if ($s_list) {
            $this->response('1', '操作成功', $s_list);
        } else {
            $this->response('1', '暂无数据', $s_list);
        }
    }

    /**
     * @api {post} /api/app.ArticleManage/getArticleListV2 文章列表(app)
     * @apiVersion 1.0.0
     * @apiName getArticleListV2
     * @apiGroup APi
     * @apiDescription 文章列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} page 当前页数.
     * @apiParam {string} pagesize 分页数.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
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
     *      cover: 封面图
     *      summary:摘要
     *      publish_time: 预发布日期
     * }
     * ]
     * }
     *
     */
    public function getArticleListV2()
    {
        $status = input('param.status', '-1', 'int');
        $term_type = input('param.term_type', '4', 'int');
        $category_id = input('param.category_id', '4', 'int');
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        if ($status > 0) {
            $where['status'] = $status;
        }
        if ($term_type > 0) {
            $where['term_type'] = $term_type;
        }
        $industry_category = config('app_config.industry_category');
        $media_category = config('app_config.media_category');
        $company_category = config('app_config.company_category');
        //测试数据

//        if ($category_id == 1) {
//            $where['category_id'] = $industry_category;
//        } elseif ($category_id == 2) {
//            $where['category_id'] = $media_category;
//        } elseif ($category_id == 3) {
//            $where['category_id'] = $company_category;
//        } elseif ($category_id == 4){
//            $category = $industry_category.','.$company_category;
//            $where['category_id'] = ['in', $category];
//        }
        $field = 'id,category_id,cover,article_id,status, publish_time';
        $list = $this->getPageList('TermArticle', $where, 'publish_time desc', $field, $pagesize);
        foreach ($list['list'] as $key=> $value) {
            $info = DB::name('Article')->where(['id'=>$value['article_id']])->find();
            $list['list'][$key]['title'] = $info['title'];
            $list['list'][$key]['summary'] = $info['summary'];
            $list['list'][$key]['publish_time'] = date('Y-m-d H:i:s', $value['publish_time']);
        }
        if ($list['count'] > 0) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '暂无数据');
        }
    }


    /**
     * @api {post} /Api/app.ArticleManage/articleInfo 文章详情
     * @apiVersion              1.0.0
     * @apiName                 articleInfo
     * @apiGROUP                APi
     * @apiDescription          文章详情
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id   文章id
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
        $article = new Article();
        $id = input('param.id', '');
        $ids = explode(',', $id);
        foreach ($ids as $key=>$value) {
            $article->addViewCount($value);
            $a_info = DB::name('Article')->where(['id'=>$value])->find();
            if($a_info) {
                $infos = $article->getArticleInfo($value,2);
                $info[$key] = $infos;
                $info[$key]['publish_time'] = wordTime($infos['publish_time']);
                $info[$key]['content_web'] = 'http://' . $_SERVER['SERVER_NAME'] . '/Api/app.ArticleManage/articleContent?id=' . $value;
            }

        }
        $info = array_values($info);
        if(count($info) ==1) {
            $info = $info[0];
        }
        if ($info) {
            $this->response('1', '操作成功', $info);
        } else {
            $this->response('1', '暂无数据', $info);
        }
    }

    public function articleContent()
    {
        $id = input('param.id', '1', 'int');
        //过滤参数文章没有关联的情况
        $info = DB::name('Article')->where(['id'=>$id])->find();
        $this->assign('info', $info);
        return $this->fetch('App/Article');
    }


    /**
     * @api {post} /Api/app.ArticleManage/videoInfo 视频详情
     * @apiVersion              1.0.0
     * @apiName                 videoInfo
     * @apiGROUP                APi
     * @apiDescription          视频详情
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id   视频id
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
     *   title: 标题,
     *   description: 描述,
     *   publish_time: 发布时间,
     *   content: 视频地址,
     *   tags:标签
     * }
     *
     */

    public function videoInfo()
    {
        $video = new Video();
        $id = input('param.id', '', 'int');
        $info = $video->getVideoInfo($id);
        if (empty($info) ) {
            $this->response('1', '暂无数据');
        }
        $info['publish_time'] = wordTime(strtotime($info['create_time']));
        if ($info) {
            $this->response('1', '操作成功', $info);
        } else {
            $this->response('1', '暂无数据', $info);
        }
    }
}
