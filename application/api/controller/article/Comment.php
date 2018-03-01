<?php
namespace app\api\controller\article;

use think\Db;
use app\common\controller\Api;
use app\article\model\Commentary;
use app\article\model\Article;
use app\article\model\Category;
use app\article\model\Term;

class Comment extends Api
{
    /**
     * @api {post} /Api/article.comment/termcategoryList app专家分类列表
     * @apiVersion              1.0.0
     * @apiName                 termcategoryList
     * @apiGROUP                APi
     * @apiDescription          app专家分类列表(pid =149）
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
     *          id   类别ID
     *          title 名称
     * }
     * ]
     * }
     *
     */
    public function termCategoryList()
    {
        $category = new Category();
        $pid = 149;//149定为专家
        if($pid < 0) {
            $this->response('1', '获取成功');
        }
        $where = ['term_type'=>2, 'pid'=>$pid, 'status'=>1];
        $field = 'id,name';
        $map[] = ['id'=> $pid, 'name'=>'全部'];
        $list = $map;
        $list = $category->getCategoryTermList($where, $field);
        //var_dump($list);
        $list = array_merge($map, $list);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /Api/article.comment/articleInfo 文章详情()
     * @apiVersion              1.0.0
     * @apiName                 articleInfo
     * @apiGROUP                APi
     * @apiDescription          文章详情（peak）
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
     * }
     *
     */
    public function articleInfo()
    {
        $article = new Article();
        $is_ad = input('param.is_ad', '-1', 'int');
        $id = input('param.id', '1', 'int');
        $data = $this ->getArticleInfo($id);
        $article->addViewCount($id);
        if ($data) {
            $this->response('1', '获取成功', $data);
        } else {
            $this->response('-1', '未查询到数据', $data);
        }
    }
    public function getArticleInfo($id)
    {
        $article = new Article();
        //过滤参数文章没有关联的情况
        $s_where['article_id'] = $id;
        $s_where['status'] = 1;
        $s_info = DB::name('TermArticle')->where($s_where)->find();
        if (empty($s_info) ) {
            $this->assign('info', $s_info);
            return $this->fetch('Aio/Article');
        }
        // if ($is_ad > 0) {
        //     if (! $province_id) {
        //         $this->response(-1, '省份ID不能为空');
        //     }
        //     $request['show_type'] = 1;
        //     $request['province_id'] = $province_id;
        //     $request['is_ad'] = $is_ad;
        //     $model = new Statistics();
        //     $model->adClickStatistics($request);
        // }
        // $time = strtotime($info['create_time']);
        $info['publish_time'] = wordTime($s_info['publish_time']);
        return $info;
//        $this->assign('info', $info);
//        return $this->fetch('Aio/Article');
    }

    /**
     * @api {post} /Api/article.comment/termHeadList 活动新闻列表
     * @apiVersion              1.0.0
     * @apiName                 termHeadList
     * @apiGROUP                APi
     * @apiDescription          活动新闻列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          category_id   文章类型ID
     * @apiParam {Int}          page   页数
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
     *          id   文章ID
     *          title 文章标题
     *          img 文章图片
     *          size: 图片尺寸
     *          content 文章内容
     *          publish_time 发布时间
     *          username: 笔名,
     *          avatar: 用户图标,
     *          is_ad 是否广告 （1是 0 否）
     * }
     * ]
     * }
     *
     */
    public function termHeadList()
    {
        $term = new Term();
        $category = new Category();
        $category_id = input('param.category_id', '149', 'int');
        $province_id = input('param.aio_province_id', '1', 'int');
        $where['at.term_type'] = 2;
        $where['at.status'] = 1;
        $ct_where['id'] = $category_id;
        $info = $category->getCategoryTermList($ct_where, '');
        if($info[0]['pid'] == 0) {
            $c_where['pid'] = $info['0']['id'];
            $categorys = $category->getCategoryTermIds($c_where);
            $where['category_id'] = ['in', $categorys];
        } else{
            $where['category_id'] = $category_id;
        }
        $field = 'at.id,category_id,article_id,head_img,at.publish_time';
        $page = input('param.page', '1', 'int');
        $pagesize = input('param.pagesize', '4', 'int');
        if($pagesize <= 0 || $page <= 0) {
            $this->response('-1', '分页数或当前页必须大于零');
        }
        $limit = $pagesize*($page-1).','.$pagesize;
        $where['at.publish_time'] = ['<=', time()];
        $result = [];
        $id ='';
        $list = $term->getTermList($where, $field, $limit, ['is_top'=>'desc', 'id'=>'desc'],$province_id);
       
       
       foreach ($list as $key => $value) {
            $info = $this->getArticleInfo($value['id']);
         var_dump($info );
       }
      // $s_info = Db::name('Article')->where('id','in',$id)->select();
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }
    /**
     * @api {post} /Api/article.comment/getCommentList 读取全部留言列表
     * @apiVersion              1.0.0
     * @apiName                 getCommentList
     * @apiGROUP                APi
     * @apiDescription          读取全部留言列表
     * @apiParam {String}       id 文章ID，不传或传0显示全部评论列表
     * @apiParam {Int}          page   页数
     * @apiParam {int}          pagesize 分页数
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     *    "code": "1",
     *    "msg": "获取成功",
     *    "data": [
     *    {
     *      "id": 6,
     *      "article_id": 21,
     *      "member_id": 21992,
     *      "content": "评论不错，置顶",
     *      "pid": 5,
     *      "reply_user": 23,
     *      "is_top": 1,
     *      "create_time": "2017-11-23 13:46:10"
     *    },
     *    {
     *      "id": 8,
     *      "article_id": 22,
     *      "member_id": 23,
     *      "content": "what?招生文化课",
     *      "pid": 0,
     *      "reply_user": 0,
     *      "is_top": 0,
     *      "create_time": "2017-11-23 13:48:02"
     *    }
     * }
     */
    public function getCommentList()
    {
        $page = input('page', '1');
        $pageSize = input('pagesize', '10');
        $id = input('id', '0');
        $category_id = 149;
        $category = new Category();
        $term = new Term();
        $where['at.term_type'] = 2;
        $where['at.status'] = 1;
        $ct_where['id'] = $category_id;
        $info = $category->getCategoryTermList($ct_where, '');
        if($info[0]['pid'] == 0) {
            $c_where['pid'] = $info['0']['id'];
            $categorys = $category->getCategoryTermIds($c_where);
            $where['category_id'] = ['in', $categorys];
        } else{
            $where['category_id'] = $category_id;
        }
        $field = 'at.id,category_id,article_id,head_img,at.publish_time';
        $page = input('param.page', '1', 'int');
        $pagesize = input('param.pagesize', '4', 'int');
        if($pagesize <= 0 || $page <= 0) {
            $this->response('-1', '分页数或当前页必须大于零');
        }
        $limit = $pagesize*($page-1).','.$pagesize;
        $where['at.publish_time'] = ['<=', time()];
        $list = $term->getTermList($where, $field, $limit, ['is_top'=>'desc', 'id'=>'desc'],'0');
        $c_list =[];
        foreach ( $list as $k =>$v)
        {
            array_push($c_list,$v['article_id']);
        }
        $Commentary = new Commentary();
        $list = $Commentary -> readCommentById($c_list, $page, $pageSize);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }
    /**
     * @api {post} /Api/aio.AioTopic/topicHeadList 专题详情
     * @apiVersion              1.0.0
     * @apiName                 topicHeadList
     * @apiGROUP                APi
     * @apiDescription          专题详情
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id   专题id
     * @apiParam {Int}          page   页数
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
     *      id:  文章ID,
     *      title: 专题名称,
     *      list_img: 专题列表图片,
     *      description ：描述
     *      head_img: 头部图片
     *      recommend 推荐语
     *      article [{
     *          id   文章ID
     *          title 文章标题
     *          img 文章图片
     *          content 文章内容
     *          publish_time 发布时间
     *          username: 笔名,
     *          avatar: 用户图标,
     *          is_ad 是否广告 （1是 0 否）
     *      }]
     * }
     * ]
     * }
     *
     */
    public function topicHeadList()
    {
        $topic = new Topic();
        $id = input('param.id', '1', 'int');
        $province_id = input('param.aio_province_id', '', 'int');
        $page = input('param.page', '1', 'int');
        $pagesize = input('param.pagesize', '4', 'int');
        if($pagesize <= 0 || $page <= 0) {
            $this->response('-1', '分页数或当前页必须大于零');
        }
        $s_where['term_type'] = 2;
        $s_where['id'] = $id;
        $s_where['status'] = 1;
        $s_info = DB::name('Topic')->where($s_where)->find();
        if (empty($s_info) ) {
            $this->response('-1', '未查询到数据');
        }
        $limit = $pagesize*($page-1).','.$pagesize;
        $info = $topic->getTopicArticleInfo($id, $province_id, $limit);
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '未查询到数据', $info);
        }
    }
    public function topicCoverList()
    {
        $topic = new Topic();
        $topic_type = input('param.topic_type', '1', 'int');
        $pagesize = input('param.pagesize', '12', 'int');
        $page = input('param.page', '1', 'int');
        if($pagesize <= 0 || $page <= 0) {
            $this->response('-1', '分页数或当前页必须大于零');
        }
        $where = ['term_type'=>1, 'topic_type'=>$topic_type, 'status'=>1];
        $field = 'id,title,list_img,is_top,sort';
        $limit = $pagesize*($page-1).','.$pagesize;
        $list = $topic->getTopicCoverList($where, $field, $limit, ['is_top'=>'desc','sort'=>'desc', 'id'=>'desc']);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }
    public function getArrayOfArticleTitle($id)
    {
        $where['id'] = ['in', $id];
        $info = Db::name('Article')
                ->where($where)
                ->select();
    }
}