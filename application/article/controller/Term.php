<?php

namespace app\article\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Term extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /article/Term/termList 文章内容管理列表
     * @apiVersion              1.0.0
     * @apiName                 termList
     * @apiGROUP                Term
     * @apiDescription          文章内容管理列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       title 标题名称（可选）
     * @apiParam {Int}          status   状态（1：正常，0：禁用）可选
     * @apiParam {Int}          term_type 终端类型（1一体机 2 APP 3 校园官网）
     * @apiParam {Int}          category_id 分类ID
     * @apiParam {String}       pagesize 分页数（可选）
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
     *      c_name: 二级分类,
     *      t_name: 终端,
     *      c_t_name: 一级分类
     *      cover: 封面
     *      view_count: 阅读数
     *      author_name 编辑
     *      sort: 排序
     *      create_time :发布日期
     *      publish_time: 预约发布时间
     *      is_top: 是否置顶
     *
     *
     * }
     * ]
     * }
     *
     */
    public function termList()
    {
        $title = input('param.title');
        $status = input('param.status', '-1', 'int');
        $pagesize = input('param.pagesize', '10', 'int');
        $type = input('param.type', '', 'int');
        $term_type = input('param.term_type', '-1', 'int');
        $category_id = input('param.category_id', '-1', 'int');
        $category_top_id = input('param.category_top_id', '-1', 'int');
        $wheres = '';
        if ($status >= 0) {
            $wheres['status'] = $status;
        }
        //过滤内容数据
        $where['status'] = 1;
        if (!empty($title)) {
            $where['title'] = ['like', "%" . $title . "%"];
            $ids = model('Article')->getArticleFromTitle($where);
            $wheres['article_id'] = ['in', $ids];
        }
        //过滤分类数据
        $c_where['status'] = 1;
        $cids = model('Category')->getCategoryTermIds($c_where);
        $wheres['category_id'] = ['in', $cids];

        if ($type == 1) {
            $post_ids = DB::name('Ad')->where(['show_type' => 2])->column('post_id');
            $wheres['id'] = ['NOT IN', $post_ids];
        }
        if ($term_type > 0) {
            $wheres['term_type'] = $term_type;
        }

        if ($category_top_id > 0) {
            $t_where['pid'] = $category_top_id;
            $tids = model('Category')->getCategoryTermIds($t_where);
            $wheres['category_id'] = ['in', array_merge(array($category_top_id), $tids)];
        }

        if ($category_id > 0) {
            $wheres['category_id'] = $category_id;
        }

        $list = $this->getPageList('TermArticle', $wheres, 'id desc', '*', $pagesize);
        foreach ($list['list'] as $key => $value) {
            $a_info = model('Article')->getArticleInfo($value['article_id']);
            $t_info = model('Term')->getTermType($value['term_type']);
            $c_info = model('Category')->getCategoryInfo($value['category_id']);
            $is_top = ['否', '是'];
            $list['list'][$key]['is_top'] = $is_top[$value['is_top']];
            $list['list'][$key]['t_name'] = $t_info['name'];
            $list['list'][$key]['c_name'] = $c_info['name'];
            if (isset($c_info['catrgory_top_name'])) {
                $list['list'][$key]['c_t_name'] = $c_info['catrgory_top_name'];
            } else {
                $list['list'][$key]['c_t_name'] = '';
            }
            $list['list'][$key]['title'] = $a_info['title'];
            $list['list'][$key]['author_name'] = $a_info['username'];
            $list['list'][$key]['view_count'] = $a_info['view_count'];
            $list['list'][$key]['zan_count'] = $a_info['zan_count'];
            $list['list'][$key]['sort'] = $a_info['sort'];
            $list['list'][$key]['publish_time'] = date("Y-m-d H:i", $value['publish_time']);
            $list['list'][$key]['create_time'] = $a_info['create_time'];

        }

        if ($list['count'] > 0) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /article/Term/addTerm 添加资讯文章
     * @apiVersion              1.0.0
     * @apiName                 addTerm
     * @apiGROUP                Term
     * @apiDescription          添加资讯文章
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          term_type   终端类型
     * @apiParam {String}       is_top   是否置顶
     * @apiParam {String}       cover   封面，列表页图片url地址
     * @apiParam {String}       head_img   文章头图图片url地址
     * @apiParam {String}       article_id   文章ID
     * @apiParam {Int}          category_id   文章分类子类id
     * @apiParam {Int}          size   图片比例（宽/高）
     * @apiParam {Int}          publish_time 发布时间
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function addTerm()
    {
        //   $info = [
        //     [
        //         'term_type' => '2',
        //         'is_top' => '0',
        //         'cover' => 'http://orvv6n9w4.bkt.clouddn.com/20170906/9739289f0ed2e14d0c502d56ddc90b76.png',
        //         'head_img' => 'http://orvv6n9w4.bkt.clouddn.com/20170906/fb86bf512a7284da9c5ac4a0ac4ffa6c.jpg',
        //         'article_id' => '27',
        //         'category_id' => '18',
        //         'publish_time' => '0',
        //         'size' => '1',
        //     ],[
        //         'term_type' => '3',
        //         'is_top' => '0',
        //         'cover' => 'http://orvv6n9w4.bkt.clouddn.com/20170906/80678018d99de681bad6dbc1c8344708.jpg',
        //         'article_id' => '27',
        //         'category_id' => '17',
        //         'publish_time' => '0',
        //     ]
        // ];
        $info = input('param.');
        foreach ($info as $key => $value) {
            if (!isset($value['head_img'])) {
                $value['head_img'] = '';
                $value['size'] = '0.00';
            }
            $info = model('Term')->getUniqueTerm($value['article_id'], $value['term_type']);
            if (empty($info)) {
                if ($value['size'] == '') {
                    $value['size'] = '0.00';
                } else {
                    $value['size'] = number_format($value['size'], 2);
                }
                if ($value['publish_time'] == '' || $value['publish_time'] <= time()) {
                    $value['publish_time'] = time();
                }
                krsort($value);
                $infos[] = $value;
            } else {
                $t_info = model('Term')->getTermType($value['term_type']);
                $value['t_name'] = $t_info['name'];
                $unique_info[] = $value;
            }
        }
        if (empty($infos)) {
            // dd($unique_info);
            $this->response('-1', '数据提交重复', array_column($unique_info, 't_name'));
        }
        $res = DB::name('TermArticle')->insertAll($infos);
        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

    /**
     * @api {post} /article/Term/editTerm 编辑资讯文章
     * @apiVersion              1.0.0
     * @apiName                 editTerm
     * @apiGROUP                Term
     * @apiDescription          编辑资讯文章
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id 专题ID
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
     *      id: 专题id,
     *      category_top_id: 一级分类,
     *      category_id: 二级分类,
     *      term_type: 终端,
     *      is_top: 是否置顶,
     *      cover: 封面，列表页图片url地址,
     *      head_img: 文章头图图片url地址,
     *      article_id 文章ID
     *      publish_time 发布时间
     * }
     * ]
     * }
     *
     */
    public function editTerm()
    {
        $id = input('param.id', '', 'int');
        $info = model('Term')->getTermInfoById($id);
        $c_info = model('Category')->getCategoryInfo($info['category_id']);
        $term_info = model('Term')->getTermTypeColumnInfo();
        $info['term_name'] = $term_info[$info['term_type']];
        $info['catrgory_name'] = $c_info['name'];
        $info['category_id'] = (string)$c_info['id'];
        if (isset($c_info['catrgory_top_name'])) {
            if ($c_info['pid'] == 0) {
                $info['category_id'] = '';
                $info['catrgory_name'] = '';
            }
            $info['category_top_id'] = (string)$c_info['catrgory_top_id'];
            $info['catrgory_top_name'] = $c_info['catrgory_top_name'];
        } else {
            $info['category_top_id'] = ' ';
            $info['category_top_name'] = '';
        }
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据');
        }
    }

    /**
     * @api {post} /article/Term/saveTopic 修改提交资讯文章
     * @apiVersion              1.0.0
     * @apiName                 saveTopic
     * @apiGROUP                Term
     * @apiDescription          修改提交资讯文章
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          term_type   终端类型)
     * @apiParam {String}       is_top   是否置顶
     * @apiParam {String}       cover   封面，列表页图片url地址
     * @apiParam {String}       head_img   文章头图图片url地址
     * @apiParam {String}       article_id   文章ID
     * @apiParam {Int}          category_id   文章分类子类id
     * @apiParam {Int}          id   资讯文章id
     * @apiParam {Int}          size   图片比例（宽/高）
     * @apiParam {Int}          publish_time 发布时间
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function saveTopic()
    {
        // $info = [
        //     'term_type'=>3,
        //     'is_top'=>3,
        //     'top_expire'=>3,
        //     'cover'=>3,
        //     'head_img'=>3,
        //     'article_id'=>1,
        //     'category_id'=>3,
        //     'id'=>54,
        // ];
        $info = input('param.');
        if($info['publish_time'] =='' || $info['publish_time'] <= time()) {
            $info['publish_time'] = time();
        }
        if (empty($info['category_id'])) {
            $info['category_id'] = $info['category_top_id'];
        }
        $res = DB::name('TermArticle')->update($info);
        $s_info = DB::name('TermArticle')->where(['id' => $info['id']])->find();
        if ($s_info['status'] == 1 && $s_info['term_type'] == 1) {
            model('common')->sendPushToR40('', 'UPDATE_TERM_MENU');
        }
        if ($res !== false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

    /**
     * @api {post} /article/Term/deleteTopic 设置文章交资讯文章状态
     * @apiVersion              1.0.0
     * @apiName                 deleteTopic
     * @apiGROUP                Term
     * @apiDescription          设置文章交资讯文章状态
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id   资讯文章id(多个','分割)
     * @apiParam {Int}          status   状态（1：正常，0：禁用）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     *
     */
    public function deleteTopic()
    {
        $id = input("param.id");
        $status = input("param.status", '1', 'intval');
        if (empty($id)) {
            $this->response('-1', '数据不能为空');
        }
        $res = DB::name('TermArticle')
            ->where('id', 'in', $id)
            ->update(['status' => $status]);

        //推送一体机
        $info = DB::name('TermArticle')
            ->where('id', 'in', $id)
            ->where(['term_type' => 1])
            ->find();
        if ($info) {
            model('common')->sendPushToR40('', 'UPDATE_TERM_MENU');
        }
        if ($status == 0) {
            DB::name('SlideShow')
                ->where('obj_value', 'in', $id)
                ->where(['jump_obj' => 1])
                ->update(['status' => $status]);
        }
        if ($res !== false) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }

    /**
     * @api {post} /article/Term/termTypeList 终端列表
     * @apiVersion              1.0.0
     * @apiName                 termTypeList
     * @apiGROUP                Term
     * @apiDescription          终端列表
     *
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       type 模块类型（1-文章内容管理 2-文章内容管理 3-轮播图片管理 4-视频内容管理 5-期刊内容管理 0 全部）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *      id: 终端ID,
     *      name: 终端名称,
     * }
     * ]
     * }
     *
     */
    public function termTypeList()
    {
        $param['type'] = input('param.type', '0', 'int');
        //配置内容模块对应终端显示
        //0-所有 1-文章内容管理 2-专题内容管理 3-轮播图片管理 4-视频内容管理 5-期刊内容管理 6-问题反馈
        $relation = [
            '0'=>['1','2','3','4','5','6'],
            '1'=>['1','2','3','4','5'],
            '2'=>['1','2','3','4','5'],
            '3'=>['1','2','3','4','5'],
            '4'=>['1','2','3','4','5'],
            '5'=>['1','6'],
            '6'=>['3','4']
        ];
        $where['id'] = ['in', $relation[$param['type']]];
//        dd($where);
        $list = model('term')->getTermTypeInfo($where);
        foreach ($list as $key => $value) {
            $list[$key]['id'] = (string)$value['id'];
        }
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }

    }

}
