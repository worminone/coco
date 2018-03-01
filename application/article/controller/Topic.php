<?php
namespace app\article\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Topic extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /article/Topic/topicList 查看专题列表
     * @apiVersion              1.0.0
     * @apiName                 topicList
     * @apiGROUP                Topic
     * @apiDescription          查看专题列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       title 标题名称（可选）
     * @apiParam {int}          status   状态（1：正常，0：禁用）可选
     * @apiParam {int}          topic_type   专题类型（1普通专题 2大学专题 3专业专题）
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {String}       pagesize 分页数（可选）
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
     *      id:  专题ID,
     *      index_img: 一体机首页图片地址,
     *      title: 专题名称,
     *      term_name: 终端名称,
     *      create_time: 发布时间,
     *      author_name: 作者名称
     *      sort  排序
     *      is_top 是否置顶
     *      status 状态
     *      term_type_name: 专题分类名称
     *      topic_type: 专题分类(1专题 2专业专题 3大学专题)
     * }
     * ]
     * }
     *
     */
    public function topicList()
    {
        $title = input('param.title');
        $pagesize = input('param.pagesize', '10', 'int');
        $status = input('param.status', '-1', 'int');
        $topic_type = input('param.topic_type', '-1', 'int');
        $where = [];
        if($status>=0) {
            $where['status'] = $status;
        }
        if($topic_type>=0) {
            $where['topic_type'] = $topic_type;
        }
        if(!empty($title)) {
            $where['title'] = ['like', "%".$title.'%'];
        }
        $list = $this->getPageList('Topic', $where, 'id desc', '*', $pagesize);
        foreach ($list['list'] as $key => $value) {
            $t_info = model('Term')->getTermType($value['term_type']);
            $list['list'][$key]['term_name'] =  $t_info['name'];
            $is_top = ['否', '是'];
            $list['list'][$key]['is_top'] = $is_top[$value['is_top']];
            $topic_type = model('Topic')->getTopicType();
            $list['list'][$key]['term_type_name'] = $topic_type[$value['topic_type']];
            $author_name = DB::name('UserAuthor')->where(['id'=>$value['author_id']])->column('name');
            if($author_name) {
                $author_name = $author_name[0];
            } else {
                $author_name = '匿名';
            }
            $list['list'][$key]['author_name'] = $author_name;
        }
        if ($list['count'] > 0) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /article/Topic/addTopic 新增专题
     * @apiVersion              1.0.0
     * @apiName                 addTopic
     * @apiGROUP                Topic
     * @apiDescription          新增专题
     *
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {String}    title   专题名称
     * @apiParam {Int}       term_type   终端形式
     * @apiParam {Int}       sort   排序
     * @apiParam {Int}       topic_type: 专题分类(1专题 2专业专题 3大学专题)
     * @apiParam {Int}       is_top   是否置顶(1:置顶，0:不置顶)
     * @apiParam {String}    index_img   一体机首页图片地址
     * @apiParam {String}    list_img   专题列表封面图片地址
     * @apiParam {String}    head_img   专题头部位置图片地址
     * @apiParam {String}    author_id   作者ID
     * @apiParam {String}    description   描述
     * @apiParam {String}    recommend  推荐语
     * @apiParam {String}    collegeName:大学名称.
     * @apiParam {Int}       term_article_id 文章ID(多个用','分割)
     *==================专业===================
     * @apiParam {String}    college_id:大学ID.
     * @apiParam {String}    majorNumber: 专业代码,
     * @apiParam {String}    majorName:专业名称,
     * @apiParam {String}    academic_degree: 授予学位,
     * @apiParam {String}    needYear: 修学年限,
     * @apiParam {String}    type_name: 专业子类,
     * @apiParam {String}    top_name: 所属大类
     * @apiParam {String}    type_number: 专业子类代码,
     * @apiParam {String}    top_number: 所属大类代码
     * @apiParam {String}    degree: 学位
     * @apiParam {int}       major_people: 专业人数
     * @apiParam {String}    employment_rate: 就业率
     * @apiParam {String}    popularity:人气
     *====================大学====================
     * @apiParam {String}    college_id:大学ID.
     * @apiParam {String}    collegeName:大学名称.
     * @apiParam {String}    city:城市.
     * @apiParam {String}    thumb:院校图标.
     * @apiParam {String}    schools_type:院校类别 
     * @apiParam {String}    collegeNature:办学性质.
     * @apiParam {String}    collegesAndUniversities:院校隶属.
     * @apiParam {String}    master_num:硕士点数.
     * @apiParam {String}    doctor_num:博士点数.
     * @apiParam {String}    academician_num:院士人数.
     * @apiParam {Int}       subject_num:重点学科数量.
     * @apiParam {String}    college_degree: 学历层次,
     * @apiParam {Int}       ranking:综合排名.
     * @apiParam {Int}       environment 校园环境.
     * @apiParam {Int}       traffic:交通指数.
     * @apiParam {Int}       hardware:硬件设施.
     * @apiParam {Int}       life:生活便利.
     * @apiParam {Int}       study:学习环境.
     *
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */
    public function addTopic()
    {
        $info = input('param.');
        if($info['topic_type'] == 3) {
            $major_info = [
                'college_id'=>$info['college_id'],
                'collegeName'=>$info['collegeName'],
                'majorNumber'=>$info['majorNumber'],
                'majorName'=>$info['majorName'],
                'academic_degree'=>$info['academic_degree'],
                'needYear'=>$info['needYear'],
                'type_name'=>$info['type_name'],
                'top_name'=>$info['top_name'],
                'type_number'=>$info['type_number'],
                'top_number'=>$info['top_number'],
                // 'degree'=>$info['degree'],
                'major_people'=>$info['major_people'],
                'employment_rate'=>$info['employment_rate'],
                'popularity'=>$info['popularity']
            ];
            $info['config_info'] = json_encode($major_info);
        }
        if($info['topic_type']==2) {
            $college_info = [
                'college_id'=>$info['college_id'],
                'collegeName'=>$info['collegeName'],
                'thumb'=>$info['thumb'],
                'city'=>$info['city'],
                'schools_type'=>$info['schools_type'],
                'collegeNature'=>$info['collegeNature'],
                'collegesAndUniversities'=>$info['collegesAndUniversities'],
                'master_num'=>$info['master_num'],
                'doctor_num'=>$info['doctor_num'],
                'academician_num'=>$info['academician_num'],
                'subject_num'=>$info['subject_num'],
                'college_degree'=>$info['college_degree'],
                'ranking'=>$info['ranking'],
                'environment'=>$info['environment'],
                'traffic'=>$info['traffic'],
                'hardware'=>$info['hardware'],
                'life'=>$info['life'],
                'study'=>$info['study']
            ];
            $info['config_info'] = json_encode($college_info);
        }
        DB::name('Topic')->insert($info);
        $id = Db::name('Topic')->getLastInsID();
        $term_article_ids = explode(',', $info['term_article_id']);
        $article_topic = '';
        for ($i=0; $i < count($term_article_ids); $i++) { 
            $article_topic[$i]['topic_id'] = $id;
            $article_topic[$i]['term_article_id'] = $term_article_ids[$i];
        }
        $res = DB::name('ArticleTopic')->insertAll($article_topic);
        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

    /**
     * @api {post} /article/Topic/editTopic 编辑查看专题
     * @apiVersion              1.0.0
     * @apiName                 editTopic
     * @apiGROUP                Topic
     * @apiDescription          编辑查看专题
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
     *      id : 专题ID
     *      term_type : 终端类型
     *      topic_type  文章分类或者专题分类,(1:文章,2:专题 3轮播图)
     *      title : 分类标题
     *      sort : 分类上级(pid=0 分类上级隐藏)
     *      is_top : 描述
     *      index_img : 一体机首页图 
     *      list_img : 大学列表封面
     *      head_img : 大学头部位置
     *      description : 描述
     *      term_name: 终端名称
     *      topic_type_name: 专题名称
     *      recommend:  推荐语
     *      collegeName:大学名称.
     *
     *      article 内容信息
     *            [{
     *                id:  文章ID
     *                title: 文章名称
     *            }]
     *==================专业===================
     *       college_id:大学ID.
     *       majorNumber: 专业代码,
     *       majorName:专业名称,
     *       academic_degree: 授予学位,
     *       needYear: 修学年限,
     *       type_name: 专业子类,
     *       top_name: 所属大类
     *       type_number: 专业子类代码,
     *       top_number: 所属大类代码
     *       degree: 学位
     *       major_people: 专业人数
     *       employment_rate: 就业率
     *       popularity:人气
     *====================大学====================
     *       college_id:大学ID.
     *       city:城市.
     *       thumb:院校图标.
     *       schools_type:院校类别 
     *       collegeNature:办学性质.
     *       collegesAndUniversities:院校隶属.
     *       master_num:硕士点数.
     *       doctor_num:博士点数.
     *       academician_num:院士人数.
     *       subject_num:重点学科数量.
     *       college_degree: 学历层次,
     *       ranking:综合排名.
     *       environment 校园环境.
     *       traffic:交通指数.
     *       hardware:硬件设施.
     *       life:生活便利.
     *       study:生活便利.
     * }
     * ]
     * }
     *
     */
    public function editTopic()
    {
        $id = input('param.id');
        $info = model('Topic')->getTopicInfo($id);
        $info['topic_type'] = (string)$info['topic_type'];
        $info['author_id'] = (string)$info['author_id'];
        $where['id'] = $info['author_id'];
        $a_info = DB::name('UserAuthor')->where($where)->find();
        if($a_info) {
            $info['author_name'] = $a_info['name'];
        } else {
            $info['author_name'] = '';
        }
        if($info['topic_type'] != 1) {
            $config_info = json_decode($info['config_info'], true);
            $info['config_info'] = $config_info;
        }
        $info['article'] = model('Topic')->getTopicArticleList($id);
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据');
        }
    }

    /**
     * @api {post} /article/Topic/saveTopic 提交修改专题
     * @apiVersion              1.0.0
     * @apiName                 saveTopic
     * @apiGROUP                Topic
     * @apiDescription          提交修改专题
     *
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {Int}       id   专题ID
     * @apiParam {String}    title   专题名称
     * @apiParam {Int}       term_type   终端形式
     * @apiParam {Int}       sort   排序
     * @apiParam {Int}       topic_type: 专题分类(1专题 2专业专题 3大学专题)
     * @apiParam {Int}       is_top   是否置顶(1:置顶，0:不置顶)
     * @apiParam {String}    index_img   一体机首页图片地址
     * @apiParam {String}    list_img   专题列表封面图片地址
     * @apiParam {String}    head_img   专题头部位置图片地址
     * @apiParam {String}    author_id   作者id
     * @apiParam {String}    description   描述
     * @apiParam {String}    recommend  推荐语
     * @apiParam {Int}       term_article_id 文章ID(多个用','分割)
     * @apiParam {String}    collegeName:大学名称.
     *==================专业===================
     * @apiParam {String}    college_id:大学ID.
     * @apiParam {String}    majorNumber: 专业代码,
     * @apiParam {String}    majorName:专业名称,
     * @apiParam {String}    academic_degree: 授予学位,
     * @apiParam {String}    needYear: 修学年限,
     * @apiParam {String}    type_name: 专业子类,
     * @apiParam {String}    top_name: 所属大类
     * @apiParam {String}    type_number: 专业子类代码,
     * @apiParam {String}    top_number: 所属大类代码
     * @apiParam {String}    degree: 学位
     * @apiParam {int}       major_people: 专业人数
     * @apiParam {String}    employment_rate: 就业率
     * @apiParam {String}    popularity:人气
     *====================大学====================
     * @apiParam {Int}      college_id:大学ID.
     * @apiParam {String}    city:城市.
     * @apiParam {String}    thumb:院校图标.
     * @apiParam {String}    schools_type:院校类别 
     * @apiParam {String}    collegeNature:办学性质.
     * @apiParam {String}    collegesAndUniversities:院校隶属.
     * @apiParam {Int}       master_num:硕士点数.
     * @apiParam {Int}       doctor_num:博士点数.
     * @apiParam {Int}       subject_num:重点学科数量.
     * @apiParam {Int}       academician_num:院士人数.
     * @apiParam {String}    college_degree: 学历层次,
     * @apiParam {Int}       ranking:综合排名.
     * @apiParam {Int}       environment 校园环境.
     * @apiParam {Int}       traffic:交通指数.
     * @apiParam {Int}       hardware:硬件设施.
     * @apiParam {Int}       life:生活便利.
     * @apiParam {Int}       study:生活便利.
     *
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */
    public function saveTopic()
    {
        $info = input('param.');

        if($info['topic_type'] == 3) {
            $major_info = [
                'college_id'=>$info['college_id'],
                'collegeName'=>$info['collegeName'],
                'majorNumber'=>$info['majorNumber'],
                'majorName'=>$info['majorName'],
                'type_number'=>$info['type_number'],
                'top_number'=>$info['top_number'],
                'academic_degree'=>$info['academic_degree'],
                'needYear'=>$info['needYear'],
                'type_name'=>$info['type_name'],
                'top_name'=>$info['top_name'],
                // 'degree'=>$info['degree'],
                'major_people'=>$info['major_people'],
                'employment_rate'=>$info['employment_rate'],
                'popularity'=>$info['popularity']
            ];
            $info['config_info'] = json_encode($major_info);
        }
        if($info['topic_type']==2) {
            $college_info = [
                'college_id'=>$info['college_id'],
                'collegeName'=>$info['collegeName'],
                'thumb'=>$info['thumb'],
                'city'=>$info['city'],
                'schools_type'=>$info['schools_type'],
                'collegeNature'=>$info['collegeNature'],
                'collegesAndUniversities'=>$info['collegesAndUniversities'],
                'master_num'=>$info['master_num'],
                'doctor_num'=>$info['doctor_num'],
                'academician_num'=>$info['academician_num'],
                'subject_num'=>$info['subject_num'],
                'college_degree'=>$info['college_degree'],
                'ranking'=>$info['ranking'],
                'environment'=>$info['environment'],
                'traffic'=>$info['traffic'],
                'hardware'=>$info['hardware'],
                'life'=>$info['life'],
                'study'=>$info['study']
            ];
            $info['config_info'] = json_encode($college_info);
        }

        DB::name('Topic')->update($info);
        $term_article_ids = explode(',', $info['term_article_id']);
        DB::name('ArticleTopic')
            ->where(['topic_id'=>$info['id']])
            ->delete();
        $article_topic='';
        for ($i=0; $i < count($term_article_ids); $i++) { 
            $article_topic[$i]['topic_id'] = $info['id'];
            $article_topic[$i]['term_article_id'] = $term_article_ids[$i];
        }
        $res = DB::name('ArticleTopic')->insertAll($article_topic);
        $s_info = DB::name('Topic')->where(['id'=>$info['id']])->find();
        if($s_info['status'] == 1 && $s_info['term_type'] == 1) {
            model('common')->sendPushToR40('','UPDATE_TOPIC_MENU');
        }
         if ($res !==false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

    /**
     * @api {post} /article/Topic/deleteTopic 设置专题状态
     * @apiVersion              1.0.0
     * @apiName                 deleteTopic
     * @apiGROUP                Topic
     * @apiDescription          设置专题状态
     *
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {Int}       id   专题ID
     * @apiParam {Int}       status   状态（1：正常，0：禁用）
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */
    public function deleteTopic()
    {
        $id = input("param.id");
        $status = input("param.status", '1','intval');
        if(empty($id)) {
            $this->response('-1', '数据不能为空');
        }
        $res = DB::name('Topic')
            ->where('id', 'in', $id)
            ->update(['status'=>$status]);

        //推送一体机
        $info = DB::name('Topic')
            ->where('id', 'in', $id)
            ->where(['term_type'=>1])
            ->find();
        if($info) {
            model('common')->sendPushToR40('','UPDATE_TOPIC_MENU');
        }
        if($status == 0) {
            DB::name('SlideShow')
                ->where('obj_value', 'in', $id)
                ->where('jump_obj', 'in', '2,3,4')
                ->update(['status'=>$status]);
        }
        if ($res !==false) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }


    /**
     * @api {post} /article/Topic/topicTypeList 专题分类列表
     * @apiVersion              1.0.0
     * @apiName                 topicTypeList
     * @apiGROUP                Topic
     * @apiDescription          专题分类列表
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
     *      id: 专题分类ID,
     *      name: 专题分类名称,
     * }
     * ]
     * }
     *
     */
    public function topicTypeList()
    {
        $list = model('Topic')->getTopicTypeList();
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /article/Topic/getAutoConfigCollegeTitleInfo 标题获取大院校信息
     * @apiVersion 1.0.0
     * @apiName getAutoConfigCollegeTitleInfo
     * @apiGroup Topic
     * @apiDescription 标题获取大院校信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {int}    title 标题.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  college_id:大学ID.
     *  collegeName:大学名称.
     *  city:城市.
     *  schools_type:院校类别
     *  collegeNature:办学性质.
     *  collegesAndUniversities:院校隶属.
     *  master_num:硕士点数.
     *  doctor_num:博士点数.
     *  academician_num:院士人数.
     * }
     * ]
     * }
     *
     */
    public function getAutoConfigCollegeTitleInfo()
    {
        $title = input('param.title', '', 'htmlspecialchars');
        $college_api = config('college_api');
        $url =  $college_api.'/index/CollegeAdmin/getCollegeInfoByTitle';
        $param['title'] = $title;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /article/Topic/getMajorTopList 院校获取专业大类
     * @apiVersion 1.0.0
     * @apiName getMajorInfoById
     * @apiGroup Topic
     * @apiDescription 院校获取专业大类
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int} college_id 大学ID.
     * @apiParam {int} is_hot 是否热门(可选).
     * @apiParam {int} education_type  专业类型（B本科 C专科）
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   majorTypeNumber: 大类代码,
     *   majorTypeName: 大类名称
     * ]
     * }
     *
     */
    public function getMajorTopList()
    {
        $college_id = input('param.college_id', '', 'int');
        $is_hot = input('param.is_hot', '', 'int');
        $education_type = input('param.education_type', '');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeAdmin/getCollegeMajorList';
        $param['is_hot'] = $is_hot;
        $param['education_type'] = $education_type;
        $param['college_id'] = $college_id;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /article/Topic/getMajorTypeList 院校获取专业二级大类
     * @apiVersion 1.0.0
     * @apiName getMajorTypeList
     * @apiGroup Topic
     * @apiDescription 院校获取专业二级大类
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int}    college_id 大学ID.
     * @apiParam {int}    is_hot  是否热门（不是热门 is_hot=2）.
     * @apiParam {String} majorTypeNumber 大类代码.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   majorTypeNumber: 二级大类代码,
     *   majorTypeName: 二级大类名称
     * ]
     * }
     *
     */
    public function getMajorTypeList()
    {
        $college_id = input('param.college_id', '', 'int');
        $majorTypeNumber = input('param.majorTypeNumber');
        $is_hot = input('param.is_hot', '', 'int');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeAdmin/getMajorTypeList';
        $param['college_id'] = $college_id;
        $param['is_hot'] = $is_hot;
        $param['majorTypeNumber'] = $majorTypeNumber;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /article/Topic/getMajorList 获取专业信息
     * @apiVersion 1.0.0
     * @apiName getMajorList
     * @apiGroup Topic
     * @apiDescription 获取专业信息
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {int}    college_id 大学ID.
     * @apiParam {int}    is_hot  是否热门（不是热门 is_hot=2）.
     * @apiParam {String}  majorTypeNumber 二级大类代码 
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   id :院校专业Id
     *   majorTypeNumber: 专业代码,
     *   majorTypeName: 专业名称
     * ]
     * }
     *
     */
    public function getMajorList()
    {
        $college_id = input('param.college_id', '', 'int');
        $majorTypeNumber = input('param.majorTypeNumber');
        $is_hot = input('param.is_hot', '', 'int');
        $is_class = input('param.is_class', '-1', 'int');
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/CollegeAdmin/getMajorName';
        $param['college_id'] = $college_id;
        $param['is_hot'] = $is_hot;
        $param['is_class'] = $is_class;
        $param['majorTypeNumber'] = $majorTypeNumber;
        $param['admin_key'] = $admin_key;
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /article/Topic/getSchoolType 获取院校类别
     * @apiVersion 1.0.0
     * @apiName getSchoolType
     * @apiGroup Topic
     * @apiDescription 获取院校类别
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *   majorNumber: 专业代码,
     *   id: 专业类型ID,
     *   majorName:专业名称,
     *   academic_degree: 授予学位,
     *   needYear: 修学年限,
     *   type_name: 所属学科,
     *   top_name: 所属大类
     * }
     * ]
     * }
     *
     */
    public function getSchoolType()
    {
        $college_api = config('college_api');
        $admin_key = config('admin_key');
        $url =  $college_api.'/index/college/getSchoolType';
        $param['admin_key'] = $admin_key;
        $data = $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }    
}
