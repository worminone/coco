<?php
namespace app\api\controller\edu;

use think\Db;
use app\common\controller\Api;
use app\article\model\Article;
use app\article\model\SlideShow;

class EduArticle extends Api
{
    /**
     * @api {post} /api/edu.EduArticle/getArticleList 文章列表(edu)
     * @apiVersion 1.0.0
     * @apiName getArticleList
     * @apiGroup edu
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
    public function getArticleList()
    {
        $status = input('param.status', '-1', 'int');
        $term_type = input('param.term_type', '3', 'int');
        $category_id = input('param.category_id', '4', 'int');
        $pagesize = input('param.pagesize', '10', 'int');
        $where = '';
        if ($status > 0) {
            $where['status'] = $status;
        }
        if ($term_type > 0) {
            $where['term_type'] = $term_type;
        }
        $industry_category = config('gw_config.industry_category');
        $media_category = config('gw_config.media_category');
        $company_category = config('gw_config.company_category');
        //测试数据

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
            if(!empty(config('gw_api')))  {
                $gw_api = 'www.test.com';
            } else {
                $gw_api = config('gw_api');
            }
            $list['list'][$key]['html'] = $gw_api.'/newsdetails.html?id='.$value['article_id'];
        }
        if ($list['count'] > 0) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '暂无数据');
        }
    }


    /**
     * @api {post} /api/edu.EduArticle/getArticleInfo 文章详情(edu)
     * @apiVersion 1.0.0
     * @apiName getArticleInfo
     * @apiGroup edu
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
        $s_where['term_type'] = 4;
        $s_where['article_id'] = $id;
        $s_where['status'] = 1;
        $s_info = DB::name('TermArticle')->where($s_where)->find();
        if (empty($s_info) ) {
            $this->response('1', '暂无数据');
        }
        $article->addViewCount($id);
        $info = $article->getArticleInfo($id, 4);
        $info['publish_time'] = wordTime($info['publish_time']);
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据');
        }
    }

    /**
     * @api {post} /api/edu.EduArticle/getSlide 获取轮播图(edu)
     * @apiVersion 1.0.0
     * @apiName getSlide
     * @apiGroup edu
     * @apiDescription 获取轮播图
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
     *      id:   轮播图id ,
     *      image_url: 图片地址
     * }
     * ]
     * }
     *
     */
    public function getSlide()
    {
        $where = [];
        $status = input('param.status', 1, 'intval');
        $termType = input('param.term_type', 4, 'intval');
        $pagesize = input('param.pagesize', 10, 'intval');
        $categoryId = input('get.category_id', 0);
        if ($status > 0) {
            $where['status'] = $status;
        }
        //终端类型
        if ($termType) {
            $where['term_type'] = $termType;
        }
        //分类
        if ($categoryId) {
            $where['category_id'] = $categoryId;
        }
        $field = 'id,image_url,jump_obj,obj_value';
        $data = $this->getPageList('SlideShow', $where, 'id desc', $field, $pagesize);
        $this->response('1', '获取成功', $data);
    }

    /**
     * @api {get|post} /api/edu.EduArticle/getBaseModules 获取学校套餐模块列表
     * @apiVersion              1.0.0
     * @apiName                 getBaseModules
     * @apiGROUP                SchoolManage
     * @apiDescription          获取学校套餐模块列表
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     * {
     *    id: "id",
     *    module_name: "模块名称"
     *    school_id: 学校ID
     *    school_id :模块ID
     *
     * }
     * }
     */
    public function getBaseModules()
    {
        $school_id = input('param.school_id', '', 'intval');
        $list = DB::name('SchoolModules')->where(['school_id'=>$school_id])->select();
        foreach ($list as $key=>$value) {
            $info = DB::name('Modules')->where(['id'=>$value['module_id']])->find();
            $list[$key]['module_name'] = $info['title'];
        }
        $this->response('1', '操作成功', $list);
    }
}

