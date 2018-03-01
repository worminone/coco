<?php
namespace app\api\controller\gw;

use think\Db;
use app\common\controller\Api;
use app\article\model\Article;
use app\article\model\Video;

class GwArticle extends Api
{
    /**
     * @api {post} /api/gw.GwArticle/getArticleList 文章列表(gw)
     * @apiVersion 1.0.0
     * @apiName getArticleList
     * @apiGroup gw
     * @apiDescription 文章列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} term_type 终端（1-app 2-aio 3-web ）.
     * @apiParam {Int}    category_id 分类（1-行业新闻 2-媒体新闻 3-公司新闻 4-校园在线新闻滚动 ）.
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
    public function getArticleList()
    {
        $prefix = config('database.prefix');
        $status = input('param.status', '1', 'int');
        $term_type = input('param.term_type', '3', 'int');
        $category_id = input('param.category_id', '1', 'int');
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        if ($status > 0) {
            $where['status'] = $status;
        }
        if ($term_type > 0) {
            $where['term_type'] = $term_type;
        }
        $where['publish_time'] = ['<=', time()];
        $industry_category = config('gw_config.industry_category');
        $media_category = config('gw_config.media_category');
        $company_category = config('gw_config.company_category');
        //测试数据

//       $industry_category = 149;
//       $media_category = 82;
//       $company_category = '33';
        if ($category_id == 1) {
            $where['category_id'] = $industry_category;
        } elseif ($category_id == 2) {
            $where['category_id'] = $media_category;
        } elseif ($category_id == 3) {
            $where['category_id'] = $company_category;
        } elseif ($category_id == 4){
            $category = $industry_category.','.$company_category;
            $where['category_id'] = ['in', $category];
        }
        $field = 'id,category_id,cover,article_id,status, publish_time';
        $list = $this->getPageList('TermArticle', $where, 'publish_time desc', $field, $pagesize);
        foreach ($list['list'] as $key=> $value) {
            $info = DB::name('Article')->where(['id'=>$value['article_id']])->find();
            $list['list'][$key]['title'] = $info['title'];
            $list['list'][$key]['summary'] = $info['summary'];
            $list['list'][$key]['publish_time'] = date('Y-m-d H:i:s', $value['publish_time']);
        }

        if ($list['count'] > 0) {
            $this->ajaxReturn('1', '获取成功', $list, 'jsonp');
        } else {
            $this->ajaxReturn('-1', '暂无数据', $industry_category, 'jsonp');
        }
    }


    /**
     * @api {post} /api/gw.GwArticle/getArticleInfo 文章详情(gw)
     * @apiVersion 1.0.0
     * @apiName getArticleInfo
     * @apiGroup gw
     * @apiDescription 文章详情
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} term_type 终端（1-app 2-aio 3-web ）.
     * @apiParam {Int}    category_id 分类（1-行业新闻 2-媒体新闻 3-公司新闻 ）.
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
     *   id: 1,
     *   title: 标题名称,
     *   img: 文章头图,
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
    public function getArticleInfo()
    {
        $article = new Article();
        $id = input('param.id', '', 'int');
        //过滤参数文章没有关联的情况
//        $s_where['term_type'] = 3;
        $s_where['article_id'] = $id;
        $s_where['status'] = 1;
        $s_info = DB::name('TermArticle')->where($s_where)->find();
        if (empty($s_info) ) {
            $this->ajaxReturn('-1', '暂无数据', '', 'jsonp');
        }
        $article->addViewCount($id);
        $info = $article->getArticleInfo($id,3);
        $info['publish_time'] = wordTime($info['publish_time']);
        if ($info) {
            $this->ajaxReturn('1', '获取成功', $info, 'jsonp');
        } else {
            $this->ajaxReturn('-1', '暂无数据', '', 'jsonp');
        }
    }

    /**
     * @api {post} /Api/gw.GwArticle/videoList 视频列表
     * @apiVersion              1.0.0
     * @apiName                 videoList
     * @apiGROUP                APi
     * @apiDescription          视频列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          page  页数
     * @apiParam {int}          pagesize 分页数
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id: ,
     *      title: 名称,
     *      content: "如果是play_type是1的话，就是admin_upload的id，不是就是网页或者视频源地址",
     *      play_type: 视频源来源地址,1:本地上传,2:iframe网页来源,3:外链视频源地址,
     *      cover: 封面，列表页图片url地址
     * }
     * ]
     * }
     *
     */

    public function videoList()
    {
        $video = new Video();
        $where['category_id'] = config('gw_config.video_category');
        $where['term_type'] = 3;
        $where['status'] = 1;
        $field = 'id,title,content,play_type,cover';
        $page = input('param.page', '1', 'int');
        $pagesize = input('param.pagesize', '8', 'int');
        if($pagesize <= 0 || $page <= 0) {
            $this->ajaxReturn('-1', '分页数或当前页必须大于零', '', 'jsonp');
        }
        $desc = ['is_top'=>'desc', 'sort'=>'desc', 'id'=>'desc'];
        $limit = $pagesize*($page-1).','.$pagesize;
        $list = $video->getVideoList($where, $field, $limit, $desc);
        if ($list) {
            $this->ajaxReturn('1', '获取成功', $list, 'jsonp');
        } else {
            $this->ajaxReturn('-1', '暂无数据', '', 'jsonp');
        }
    }



}

