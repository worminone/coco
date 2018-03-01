<?php
namespace app\api\controller\aio;

use think\Db;
use app\common\controller\Api;
use app\article\model\Topic;
use app\article\model\Term;
use app\article\model\Article;
use app\article\model\Video;
use app\article\model\Category;
use app\article\model\Journal;
use app\article\model\SlideShow;
use app\system\model\XyzxConfig;
use app\common\model\Statistics;

class AioTopic extends Api
{
    /**
     * @api {post} /Api/aio.AioTopic/topicCoverList 推荐回顾
     * @apiVersion              1.0.0
     * @apiName                 topicCoverList
     * @apiGROUP                APi
     * @apiDescription          推荐回顾
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       topic_type   专题类型（1.普通专题 2大学专题 3 专业专题）
     * @apiParam {int}          page 当前页
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
     * }
     * ]
     * }
     *
     */

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

    /**
     * @api {post} /Api/aio.AioTopic/journalCoverList 校刊列表
     * @apiVersion              1.0.0
     * @apiName                 journalCoverList
     * @apiGROUP                APi
     * @apiDescription          校刊列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {int}          page 当前页
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
     *      title: 校刊名称,
     *      list_img: 校刊列表图片,
     * }
     * ]
     * }
     *
     */

    public function journalCoverList()
    {
        $journal = new Journal();
        $pagesize = input('param.pagesize', '12', 'int');
        $page = input('param.page', '1', 'int');
        if($pagesize <= 0 || $page <= 0) {
            $this->response('-1', '分页数或当前页必须大于零');
        }
        $where = ['term_type'=>1, 'status'=>1];
        $field = 'id,title,list_img';
        $limit = $pagesize*($page-1).','.$pagesize;
        $list = $journal->getJournalCoverList($where, $field, $limit, ['is_top'=>'desc','sort'=>'desc', 'id'=>'desc']);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /Api/aio.AioTopic/journalInfo 期刊详情
     * @apiVersion              1.0.0
     * @apiName                 journalInfo
     * @apiGROUP                APi
     * @apiDescription          期刊详情
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
     *   'img' :[
     *           校刊图片地址
     *       ]
     * }
     *
     */
    public function journalInfo()
    {
        $journal = new Journal();
        $id = input('param.id', '1', 'int');
        $journal->addViewCount($id);
        $info = $journal->getJournalInfo($id);
        // dd($info);
        $url_list['img'] = explode(',', $info['content_url']);
        // $info['publish_time'] = wordTime($info['publish_time']);
        if ($url_list) {
            $this->response('1', '获取成功', $url_list);
        } else {
            $this->response('-1', '未查询到数据', $url_list);
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
        $s_where['term_type'] = 1;
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

    /**
     * @api {post} /Api/aio.AioTopic/articleInfo 文章详情(包括活动新闻详情)
     * @apiVersion              1.0.0
     * @apiName                 articleInfo
     * @apiGROUP                APi
     * @apiDescription          文章详情（包括活动新闻详情）
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
        $id = input('param.id', '1', 'int');
        $is_ad = input('param.is_ad', '-1', 'int');
        $province_id = input('param.aio_province_id', '', 'int');
        //过滤参数文章没有关联的情况
        $s_where['term_type'] = 1;
        $s_where['article_id'] = $id;
        $s_where['status'] = 1;
        $s_info = DB::name('TermArticle')->where($s_where)->find();
        if (empty($s_info) ) {
            $this->assign('info', $s_info);
            return $this->fetch('Aio/Article');
        }
        $article->addViewCount($id);
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
        $info = $article->getArticleInfo($id, 1);
        // $time = strtotime($info['create_time']);
        $info['publish_time'] = wordTime($info['publish_time']);
        $info['content'] = str_replace("http:","",$info['content']);
        $this->assign('info', $info);
        return $this->fetch('Aio/Article');
    }

    /**
     * @api {post} /Api/aio.AioTopic/termHeadList 活动新闻列表
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
        $category_id = input('param.category_id', '1', 'int');
        $province_id = input('param.aio_province_id', '1', 'int');
        $where['at.term_type'] = 1;
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
        // dd($where);
        $list = $term->getTermList($where, $field, $limit, ['is_top'=>'desc', 'id'=>'desc'],$province_id);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }


    /**
     * @api {post} /Api/aio.AioTopic/videoList 视频列表
     * @apiVersion              1.0.0
     * @apiName                 videoList
     * @apiGROUP                APi
     * @apiDescription          视频列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          category_id   文章类型ID
     * @apiParam {String}       title   视频标题(可选)
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

    public function videoList($category_id = '')
    {
        $video = new Video();
        $category = new Category();
        $title = input('param.title', '', 'htmlspecialchars');
        if(!$category_id) {
            $category_id = input('param.category_id', '14', 'int');
        }
        if(!empty($title)) {
            $where['title'] = ['like','%'.$title.'%'];
        }
        // $where['term_type'] = 1;
        $where['status'] = 1;
        $ct_where['id'] = $category_id;
        $info = $category->getCategoryTermList($ct_where, '');
        if($info[0]['pid'] == 0) {
            $c_where['pid'] = $info['0']['id'];
            $categorys = $category->getCategoryTermIds($c_where);
            $where['category_id'] = ['in', $categorys];
        } else{
            $where['category_id'] = $category_id;
        }
        $field = 'id,title,content,play_type,play_type content_type,cover';
        $page = input('param.page', '1', 'int');
        $pagesize = input('param.pagesize', '8', 'int');
        if($pagesize <= 0 || $page <= 0) {
            $this->response('-1', '分页数或当前页必须大于零');
        }

        $desc = ['is_top'=>'desc', 'sort'=>'desc', 'id'=>'desc'];
        $limit = $pagesize*($page-1).','.$pagesize;
        $list = $video->getVideoList($where, $field, $limit, $desc);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }
    
    /**
     * @api {post} /Api/aio.AioTopic/recruitVideoList 高招视频列表
     * @apiVersion              1.0.0
     * @apiName                 recruitVideoList
     * @apiGROUP                APi
     * @apiDescription          高招视频列表
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

    public function recruitVideoList()
    {

        $category_id = input('param.category_id', '13', 'int');
        $this->videoList($category_id);
    }

    /**
     * @api {post} /Api/aio.AioTopic/careerVideoList 生涯十八讲列表
     * @apiVersion              1.0.0
     * @apiName                 careerVideoList
     * @apiGROUP                APi
     * @apiDescription          生涯十八讲列表
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

    public function careerVideoList()
    {
        $eighteen_category = config('aio_config.eighteen_category');
        $category_id = input('param.category_id', $eighteen_category, 'int');
        $this->videoList($category_id);
    }



    /**
     * @api {post} /Api/aio.AioTopic/termcategoryList 新闻活动视频分类列表
     * @apiVersion              1.0.0
     * @apiName                 termcategoryList
     * @apiGROUP                APi
     * @apiDescription          新闻活动视频分类列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          pid   分类类型type(新闻pid = 1 活动 pid=3, 视频 pid =14）
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
    public function termcategoryList()
    {
        $category = new Category();
        $pid = input('param.pid', '1', 'int');
        $eighteen_category = config('aio_config.eighteen_category');
        if($pid < 0) {
            $this->response('1', '获取成功');
        }
        $where = ['term_type'=>1, 'pid'=>$pid, 'status'=>1];
        $field = 'id,name';
        $map[] = ['id'=> $pid, 'name'=>'全部'];
        $list = $map;
        $list = $category->getCategoryTermList($where, $field);
//        if($list) {
//            foreach ($list as $key => $value) {
//                if($value['id'] == $eighteen_category) {
//                    unset($list[$key]);
//                }
//            }
//        }
        $list = array_merge($map, $list);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }
    /**
     * @api {post} /Api/aio.AioTopic/srhTermcategoryList 新闻活动视频分类列表(app)
     * @apiVersion              1.0.0
     * @apiName                 srhTermcategoryList
     * @apiGROUP                APi
     * @apiDescription          新闻活动视频分类列表(根据文章标题文字进行搜索后展示，app4.2需求新增独立接口)
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          pid   分类类型type(视频 pid =16）
     * @apiParam {Int}          title   文章标题
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
    public function srhTermcategoryList()
    {
        $video = new Video();
        $category = new Category();
        $pid = input('param.pid', '1', 'int');
        // $pid = $pid == 14 ? 16 : $pid;//需求变动
        $title = input('param.title', '0');
        $eighteen_category = config('aio_config.eighteen_category');
        if($pid < 0) {
            $this->response('1', '获取成功');
        }
        if(!empty($title)) {
            $VideoListwhere['title'] = ['like','%'.$title.'%'];
        }
        $VideoListwhere['term_type'] = 1;
        $VideoListwhere['status'] = 1;
        $field = 'category_id,title';
        $limit = '';
        $desc = '';
        $list2 = $video->getVideoList($VideoListwhere, $field, $limit, $desc);
        $id = '';
        $where['term_type'] = 1;
        $where['pid'] = $pid;
        $where['status'] = 1;
        if(!empty($title)) {
            foreach ($list2 as $k => $value) {
                $id[$k] = $value['category_id'];
            }
            if(!empty($id))
            $id = implode(',', array_unique($id));
            $where['id'] = ['in', $id];
        }
        $field = 'id,name';
        $map[] = ['id'=> $pid, 'name'=>'全部'];
        $list = $map;
        $list = $category->getCategoryTermList($where, $field);
        // var_dump(db::name('article_category')->getlastsql());
        if($list) {
            foreach ($list as $key => $value) {
                if($value['id'] == $eighteen_category) {
                    unset($list[$key]);
                }
            }
        }
        $list = array_merge($map, $list);
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /Api/aio.AioTopic/getAutoConfigIdInfo 专题头部(大学或专业信息)
     * @apiVersion              1.0.0
     * @apiName                 getAutoConfigIdInfo
     * @apiGROUP                APi
     * @apiDescription          专题头部(大学或专业信息)
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id   专题id
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *==================专业===================
     *    majorNumber: 专业代码,
     *    major_id: 专业类型ID,
     *    majorName:专业名称,
     *    academic_degree: 授予学位,
     *    needYear: 修学年限,
     *    type_name: 专业子类,
     *    top_name: 所属大类
     *    degree: 学位
     *    major_people: 专业人数
     *    employment_rate: 就业率
     *    popularity:人气
     *====================大学====================
     *    college_id:大学ID.
     *    collegeName:大学名称.
     *    city:城市.
     *    thumb:大学图标.
     *    schools_type:院校类别
     *    collegeNature:办学性质.
     *    collegesAndUniversities:院校隶属.
     *    teacher_num:教工人数.
     *    master_num:硕士点数.
     *    doctor_num:博士点数.
     *    academician_num:院士人数.
     *    ranking:综合排名.
     *    environment 校园环境.
     *    traffic:交通指数.
     *    hardware:硬件设施.
     *    life:生活便利.
     *    study:学习环境.
     * }
     * ]
     * }
     *
     */
    public function getAutoConfigIdInfo()
    {
        $topic = new Topic();
        $id = input('param.id', '', 'htmlspecialchars');
        $s_where['term_type'] = 1;
        $s_where['id'] = $id;
        $s_where['status'] = 1;
        $s_info = DB::name('Topic')->where($s_where)->find();
        if (empty($s_info) ) {
            $this->response('-1', '未查询到数据');
        }
        $t_info = $topic->getTopicInfo($id);
        $info = json_decode($t_info['config_info'], true);
        if(isset($info['major_people'])) {
            $info['major_people'] = $info['major_people'].'人';
        }
        if(isset($info['academician_num'])) {
            $info['academician_num'] = $info['academician_num'].'人';
        }

        if(isset($info['type_number'])) {
            $type_number =  $info['type_number'];
            $admin_key = config('admin_key');
            $college_api = config('college_api');
            $url =  $college_api.'/index/CollegeAdmin/getMajorNameByNumber';
            $param['majorNumber'] = $type_number;
            $param['admin_key'] = $admin_key;
            $data = curl_api($url, $param, 'post');
            // aa($data);
            $info['type_name'] = $data['data']['majorTypeName'];
            $info['top_name'] = $data['data']['majorTopTypeName'];
        }

        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '未查询到数据', $info);
        }

    }


    /**
     * @api {post} /Api/aio.AioTopic/systemPassword 系统安全密码验证
     * @apiVersion              1.0.0
     * @apiName                 systemPassword
     * @apiGROUP                APi
     * @apiDescription          系统安全密码验证
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}          password   密码
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function systemPassword()
    {
        $password = input('param.password', '', 'htmlspecialchars');
        $info = DB::name('HomeCover')->where(['id'=>21])->find();
        if($info['img_url'] == $password) {
            $this->response('1', '验证成功');
        } else {
            $this->response('-1', '密码错误');
        }

    }

    /**
     * @api {post} /Api/aio.AioTopic/getSystemInfo 关于我们
     * @apiVersion              1.0.0
     * @apiName                 getSystemInfo
     * @apiGROUP                APi
     * @apiDescription          关于我们
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiParam {Int}          status  网站开关，1:网站可以访问，0:网站显示关闭状态
     * @apiParam {String}       title   网站标题，一句话介绍概括
     * @apiParam {String}       watchword 网站口号,网站宣传语，一句话介绍概括
     * @apiParam {String}       keyword   网站搜索引擎关键词
     * @apiParam {String}       description   网站搜索引擎描述
     * @apiParam {String}       logo   网站LOGO,299*95
     * @apiParam {String}       img404   404图片,500*300
     * @apiParam {String}       copyright   版权信息
     * @apiParam {String}       icp_num   网站备案号
     * @apiParam {String}       statistics_code   站长统计
     * @apiParam {String}       company_name   公司名称
     * @apiParam {String}       address   公司地址
     * @apiParam {String}       intro   公司简介
     * @apiParam {String}       email   公司邮箱
     * @apiParam {String}       tel   公司电话
     * @apiParam {String}       customer_qq   客服 QQ
     * @apiParam {String}       company_qq   公司qq群
     * @apiParam {String}       xyzx_pubic_wechat   校园在线公众号二维码
     * @apiParam {String}       xyzx_service_wechat   校园在线微信服务号
     * @apiParam {String}       ddzx_volunteer_wechat   大道之行志愿公众号二维码
     * @apiParam {String}       ddzx_public_wechat   大道之行微信公众号
     * @apiParam {String}       download_qr   IOS和安卓下载二维码
     */

    public function getSystemInfo()
    {
        $config = new XyzxConfig();
        $info = $config->getInfo();
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '未查询到数据', $info);
        }
    }

    /**
     * @api {post} /Api/aio.AioTopic/index 首页
     * @apiVersion              1.0.0
     * @apiName                 index
     * @apiGROUP                APi
     * @apiDescription          首页
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
     *      index_img: 首页封面图
     *      title:  标题
     *      url: 跳转链接
     * }
     * ]
     * }
     *
     */

    public function index()
    {
        $c_topic = DB::name('Topic')
            ->field('id,index_img,title,topic_type,topic_type')
            ->where(['term_type'=>1,'topic_type'=>1,'status'=>1])
            ->order(['is_top'=>'desc', '`sort`'=>'desc' ,'id'=>'desc'])
            ->find();
        $c_topic['url'] = '/api/aio.AioTopic/topicCoverList/topic_type/1';
        $m_topic = DB::name('Topic')
            ->field('id,index_img,title,topic_type')
            ->where(['term_type'=>1,'topic_type'=>2,'status'=>1])
            ->order(['is_top'=>'desc', '`sort`'=>'desc' ,'id'=>'desc'])
            ->find();
        $m_topic['url'] = '/api/aio.AioTopic/topicCoverList/topic_type/2';
        $a_topic = DB::name('Topic')
            ->field('id,index_img,title,topic_type')
            ->where(['term_type'=>1,'topic_type'=>3,'status'=>1])
            ->order(['is_top'=>'desc', '`sort`'=>'desc' ,'id'=>'desc'])
            ->find();
        $a_topic['url'] = '/api/aio.AioTopic/topicCoverList/topic_type/3';

        $journal = DB::name('Journal')
            ->field('id,index_img')
            ->where(['term_type'=>1,'status'=>1])
            ->order(['is_top'=>'desc', '`sort`'=>'desc' ,'id'=>'desc'])
            ->find();
        $journal['title'] = '校园期刊';
        $journal['url'] = '/api/aio.AioTopic/journalCoverList';
        $journal['topic_type'] = 12;

        $index_info = DB::name('HomeCover')
            ->where(['term_type'=>1])
            ->field('title,img_url')
            ->select();
        $new_category = config('aio_config.new_category');
        $activity_category = config('aio_config.activity_category');
        $video_category = config('aio_config.video_category');
        $voluntarily_category = config('aio_config.voluntarily_category');
        $career_category = config('aio_config.career_category');

        // 'new_category'=> '1',         //一体机 新闻分类ID
        // 'activity_category'=>'3',     //一体机 活动分类ID
        // 'video_category'=>'14',       //一体机 视频分类ID
        // 'voluntarily_category'=>'129' //一体机 自愿填报分类ID
        // 'career_category'=>'119'      //一体机 生涯规划分类ID
        // 'eighteen_category'=>'137',   //一体机 生涯十八讲分类ID
        $index_info[0]['url'] = '/api/aio.AioTopic/termHeadList/category_id/'.$new_category;
        $index_info[0]['index_img'] = $index_info[0]['img_url'];
        $index_info[0]['topic_type'] = 11;
        $index_info[2]['url'] = '/api/aio.AioTopic/termHeadList/category_id/'.$activity_category;
        $index_info[2]['index_img'] = $index_info[2]['img_url'];
        $index_info[2]['topic_type'] = 13;
        $index_info[3]['url'] = '/api/aio.AioTopic/videoList/category_id/'.$video_category;
        $index_info[3]['index_img'] = $index_info[3]['img_url'];
        $index_info[3]['topic_type'] = 14;
        $plan['title'] = '生涯规划';
        $plan['url'] = '/api/aio.AioTopic/termHeadList/category_id/'.$voluntarily_category;
        $plan['index_img'] = '';
        $plan['topic_type'] = 23;

        $volunte['title'] = '志愿填报';
        $volunte['url'] = '/api/aio.AioTopic/termHeadList/category_id/'.$career_category;
        $volunte['index_img'] = '';
        $volunte['topic_type'] = 26;
        $list = [
            $a_topic,
            $m_topic,
            $c_topic,
            $index_info[0],
            $journal,
            $index_info[2],
            $index_info[3],
            $plan,
            $volunte
        ];
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }

    }

    public function index41()
    {
        $c_topic = DB::name('Topic')
            ->field('id,index_img,title,topic_type,topic_type')
            ->where(['term_type'=>1,'topic_type'=>1,'status'=>1])
            ->order(['is_top'=>'desc', '`sort`'=>'desc' ,'id'=>'desc'])
            ->find();
        $c_topic['url'] = '/api/aio.AioTopic/topicCoverList/topic_type/1';
        $m_topic = DB::name('Topic')
            ->field('id,index_img,title,topic_type')
            ->where(['term_type'=>1,'topic_type'=>2,'status'=>1])
            ->order(['is_top'=>'desc', '`sort`'=>'desc' ,'id'=>'desc'])
            ->find();
        $m_topic['url'] = '/api/aio.AioTopic/topicCoverList/topic_type/2';
        $a_topic = DB::name('Topic')
            ->field('id,index_img,title,topic_type')
            ->where(['term_type'=>1,'topic_type'=>3,'status'=>1])
            ->order(['is_top'=>'desc', '`sort`'=>'desc' ,'id'=>'desc'])
            ->find();
        $a_topic['url'] = '/api/aio.AioTopic/topicCoverList/topic_type/3';

        $journal = DB::name('Journal')
            ->field('id,index_img')
            ->where(['term_type'=>1,'status'=>1])
            ->order(['is_top'=>'desc', '`sort`'=>'desc' ,'id'=>'desc'])
            ->find();
      //  $journal['title'] = '校园期刊';
        $journal['url'] = '/api/aio.AioTopic/journalCoverList';
        $journal['topic_type'] = 12;

        $index_info = DB::name('HomeCover')
            ->where(['term_type'=>1])
            ->field('title,img_url')
            ->select();
        $new_category = config('aio_config.new_category');
        $activity_category = config('aio_config.activity_category');
        $video_category = config('aio_config.video_category');
        $voluntarily_category = config('aio_config.voluntarily_category');
        $career_category = config('aio_config.career_category');

        // 'new_category'=> '1',         //一体机 新闻分类ID
        // 'activity_category'=>'3',     //一体机 活动分类ID
        // 'video_category'=>'14',       //一体机 视频分类ID
        // 'voluntarily_category'=>'129' //一体机 自愿填报分类ID
        // 'career_category'=>'119'      //一体机 生涯规划分类ID
        // 'eighteen_category'=>'137',   //一体机 生涯十八讲分类ID
        $index_info[0]['url'] = '/api/aio.AioTopic/termHeadList/category_id/'.$new_category;
        $index_info[0]['index_img'] = $index_info[0]['img_url'];
        $index_info[0]['topic_type'] = 11;
        $index_info[0]['topic_type'] = 11;

        $journal['index_img'] = $index_info[1]['img_url'];
        $journal['title'] = $index_info[1]['title'];

        $index_info[2]['url'] = '/api/aio.AioTopic/termHeadList/category_id/'.$activity_category;
        $index_info[2]['index_img'] = $index_info[2]['img_url'];
        $index_info[2]['topic_type'] = 13;
        $index_info[3]['url'] = '/api/aio.AioTopic/videoList/category_id/'.$video_category;
        $index_info[3]['index_img'] = $index_info[3]['img_url'];
        $index_info[3]['topic_type'] = 14;

//        $index_info[4]['title'] = '自我测评';
        $index_info[4]['url'] = '';
        $index_info[4]['index_img'] = $index_info[4]['img_url'];
        $index_info[4]['topic_type'] = 21;

//        $index_info[5]['title'] = '生涯智库';
        $index_info[5]['url'] = '';
        $index_info[5]['index_img'] = $index_info[5]['img_url'];
        $index_info[5]['topic_type'] = 22;

//        $index_info[6]['title'] = '生涯规划';
        $index_info[6]['url'] = '/api/aio.AioTopic/termHeadList/category_id/'.$voluntarily_category;
        $index_info[6]['index_img'] = $index_info[6]['img_url'];
        $index_info[6]['topic_type'] = 23;

//        $index_info[7]['title'] = '自愿精测';
        $index_info[7]['url'] = '';
        $index_info[7]['index_img'] = $index_info[7]['img_url'];
        $index_info[7]['topic_type'] = 24;

//        $index_info[8]['title'] = '生涯选课';
        $index_info[8]['url'] = '';
        $index_info[8]['index_img'] = $index_info[8]['img_url'];
        $index_info[8]['topic_type'] = 25;

//        $index_info[9]['title'] = '志愿填报';
        $index_info[9]['url'] = '/api/aio.AioTopic/termHeadList/category_id/'.$career_category;
        $index_info[9]['index_img'] = $index_info[9]['img_url'];
        $index_info[9]['topic_type'] = 26;

        // 清空一体机首页文字[暂时处理]
//        $index_info[0]['title'] = '';
//        $index_info[1]['title'] = '';
//        $index_info[2]['title'] = '';
//        $journal['title'] = '';

        $list = [
                $a_topic,
                $m_topic,
                $c_topic,

                $index_info[0],
                $journal,
                $index_info[2],
                $index_info[3],

                $index_info[4],
                $index_info[5],
                $index_info[6],
                $index_info[7],
                $index_info[8],
                $index_info[9]
            ];
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }

    }
}

