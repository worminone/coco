<?php
namespace app\article\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Journal extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /article/Journal/journalList 查看期刊列表
     * @apiVersion              1.0.0
     * @apiName                 journalList
     * @apiGROUP                Journal
     * @apiDescription          查看期刊列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       title 标题名称（可选）
     * @apiParam {int}          status   状态（1：正常，0：禁用）可选
     * @apiParam {int}          term_type   终端类型
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
     *      id:  期刊ID,
     *      index_img: 一体机首页图片地址,
     *      name: 期刊名称,
     *      term_name: 终端名称,
     *      create_time: 发布时间,
     *      is_top 是否置顶
     *      status 状态
     *      term_type_name: 期刊分类名称
     *      c_name 分类名称
     *      journal_price  期刊价格
     *      serial_num  期刊刊号
     *      author_name 作者名称
     *      is_top   是否置顶(1:置顶，0:不置顶)
     *      sort   排序
     *      view_count   阅读数 
     * }
     * ]
     * }
     *
     */
    public function journalList()
    {
        $title = input('param.title');
        $pagesize = input('param.pagesize', '10', 'int');
        $status = input('param.status', '-1', 'int');
        $term_type = input('param.term_type', '-1', 'int');
        $where = '';
        if($term_type >= 0) {
            $where['term_type'] = $term_type;
        }
        if($status >= 0) {
            $where['status'] = $status;
        }
        if(!empty($title)) {
            $where['title'] = ['like', "%".$title.'%'];
        }

        $list = $this->getPageList('Journal', $where, 'id desc', '*', $pagesize);
        foreach ($list['list'] as $key => $value) {
            $t_info = model('Term')->getTermType($value['term_type']);
            $list['list'][$key]['term_name'] =  $t_info['name'];
            $is_top = ['否', '是'];
            $list['list'][$key]['is_top'] = $is_top[$value['is_top']];
            $author_name = DB::name('AdminUser')->where('id','in', $value['author_id'])->column('true_name');
            $c_info = model('Category')->getCategoryInfo($value['category_id']);
            $list['list'][$key]['c_name'] = $c_info['catrgory_top_name'];
            if($author_name) {
                $author_name = implode(',', $author_name);
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
     * @api {post} /article/Journal/addJournal 新增期刊
     * @apiVersion              1.0.0
     * @apiName                 addJournal
     * @apiGROUP                Journal
     * @apiDescription          新增期刊
     *
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {String}    name   期刊名称
     * @apiParam {Int}       term_type   终端形式
     * @apiParam {Int}       category_id   分类ID  
     * @apiParam {Int}       is_top   是否置顶(1:置顶，0:不置顶)
     * @apiParam {String}    index_img   一体机首页图片地址
     * @apiParam {String}    list_img   期刊列表封面图片地址
     * @apiParam {String}    journal_price  期刊价格
     * @apiParam {String}    serial_num  期刊刊号
     * @apiParam {String}    author_id   作者id
     * @apiParam {String}    is_top   是否置顶(1:置顶，0:不置顶)
     * @apiParam {String}    sort   排序
     * @apiParam {String}    view_count   阅读数
     * @apiParam {String}    content_url   校刊内容 (多个 ','分割)
     *
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */
    public function addJournal()
    {
        $info = input('param.');
        $res = DB::name('Journal')->insert($info);

        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

    /**
     * @api {post} /article/Journal/editJournal 编辑查看期刊
     * @apiVersion              1.0.0
     * @apiName                 editJournal
     * @apiGROUP                Journal
     * @apiDescription          编辑查看期刊
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
     *     name   期刊名称
     *     term_type   终端形式
     *     category_id   分类ID  
     *     is_top   是否置顶(1:置顶，0:不置顶)
     *     index_img   一体机首页图片地址
     *     list_img   期刊列表封面图片地址
     *     author_id   作者名称
     *     content_url   校刊内容 (多个 ','分割)
     *     journal_price  期刊价格
     *     serial_num  期刊刊号
     *     c_name 分类名称
     *     author_name 作者名称
     *     is_top   是否置顶(1:置顶，0:不置顶)
     *     sort   排序
     *     view_count   阅读数
     * }
     * ]
     * }
     *
     */
    public function editJournal()
    {
        $id = input('param.id');
        $info = model('Journal')->getJournalInfo($id);
        $c_info = model('Category')->getCategoryInfo($info['category_id']);
        $info['c_name'] = $c_info['name'];
        // $author_ids = explode(',', $info['author_id']);
        // for($i=0;$i<count($author_ids);$i++)
        // {
        //     $author_ids[$i] = intval($author_ids[$i]);
        // }
        // $info['author_id'] = $author_ids;
        $info['category_id'] = (string)$info['category_id'];
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据');
        }
    }

    /**
     * @api {post} /article/Journal/saveJournal 提交修改期刊
     * @apiVersion              1.0.0
     * @apiName                 saveJournal
     * @apiGROUP                Journal
     * @apiDescription          提交修改期刊
     *
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {Int}       id   校刊ID
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {String}    name   期刊名称
     * @apiParam {Int}       term_type   终端形式
     * @apiParam {Int}       category_id   分类ID  
     * @apiParam {Int}       is_top   是否置顶(1:置顶，0:不置顶)
     * @apiParam {String}    index_img   一体机首页图片地址
     * @apiParam {String}    list_img   期刊列表封面图片地址
     * @apiParam {String}    journal_price  期刊价格
     * @apiParam {String}    serial_num  期刊刊号
     * @apiParam {String}    author_id   作者id
     * @apiParam {String}    is_top   是否置顶(1:置顶，0:不置顶)
     * @apiParam {String}    sort   排序
     * @apiParam {String}    view_count   阅读数
     * @apiParam {String}    content_url   校刊内容 (多个 ','分割)
     *
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */
    public function saveJournal()
    {
        $info = input('param.');
        $res = DB::name('Journal')->update($info);

        $s_info = DB::name('Journal')->where(['id'=>$info['id']])->find();
        if($s_info['status'] == 1 && $s_info['term_type'] == 1) {
            model('common')->sendPushToR40('','UPDATE_TERM_MENU');
        }
        if ($res !==false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

    /**
     * @api {post} /article/Journal/deleteJournal 设置期刊状态
     * @apiVersion              1.0.0
     * @apiName                 deleteJournal
     * @apiGROUP                Journal
     * @apiDescription          设置期刊状态
     *
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {Int}       id   期刊ID
     * @apiParam {Int}       status   状态（1：正常，0：禁用）
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */
    public function deleteJournal()
    {
        $id = input("param.id");
        $status = input("param.status", '1','intval');
        if(empty($id)) {
            $this->response('-1', '数据不能为空');
        }
        //推送一体机
        $info = DB::name('Journal')
            ->where('id', 'in', $id)
            ->where(['term_type'=>1])
            ->find();
        if($info) {
            model('common')->sendPushToR40('','UPDATE_TERM_MENU');
        }

        $res = DB::name('Journal')
            ->where('id', 'in', $id)
            ->update(['status'=>$status]);
        if ($res !==false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


}
