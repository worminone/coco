<?php
namespace app\article\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Category extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /article/Category/categoryList 查看分类列表
     * @apiVersion              1.0.0
     * @apiName                 articleList
     * @apiGROUP                Category
     * @apiDescription          查看分类列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {int}          term_type 终端类型（1高中升学一体机,2校园在线APP,3校园在线官网）
     * @apiParam {int}          status   状态（1：正常，0：禁用）可选
     * @apiParam {Int}          page 页码（可选），默认为1
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     * @apiSuccessExample  {json} Success-Response:
     *   {
     *   code: "1",
     *   msg: "获取成功",
     *   data: {
     *       count: 1,
     *       page_num: 1,
     *       list: [{
     *               id:  大类ID,
     *               name: 大类名称,
     *               icon:图标
     *               catrgory: [
     *               {
     *                   id: 分类ID,
     *                   name: 分类名称
     *                   icon: 图标
     *               }
     *               ...
     *           ]}
     *       ]}
     *   }
     */
    public function categoryList()
    {
        $term_type = input('param.term_type', '', 'int');
        $status = input('param.status', '1', 'int');
        $where['pid'] = 0;
        if($status >= 0) {
            $where['status'] = $status;
        }
        if(!empty($term_type)) {
            $where['term_type'] = $term_type;
        }

        $list = model('Category')->getCategoryList($where);
        if (!empty($list)) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /article/Category/getCategoryOneList 调用分类列表
     * @apiVersion              1.0.0
     * @apiName                 getCategoryOneList
     * @apiGROUP                Category
     * @apiDescription          调用分类列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {int}          term_type 终端类型（1高中升学一体机,2校园在线APP,3校园在线官网）
     * @apiParam {Int}          pid 父级ID
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
     *   id:  顶级类ID,
     *   title: 顶级类名称,
     * }
     * ]
     * }
     *
     */
    public function getCategoryOneList()
    {
        $pid = input('param.pid', '0', 'int');
        $term_type = input('param.term_type', '1', 'int');
        // $type = input('param.type', '1', 'int');
        $where = ['pid'=>$pid,
                'term_type'=>$term_type,
                'status'=>1];
        $list = model('Category')->getCategoryOneInfo($where);
        foreach ($list as $key => $value) {
            $list[$key]['id'] = (string)$value['id'];
        }
        if (!empty($list)) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据');
        }
    }

    /**
     * @api {post} /article/Category/addCategory 添加分类
     * @apiVersion              1.0.0
     * @apiName                 addCategory
     * @apiGROUP                Category
     * @apiDescription          添加分类
     * level 1级 分类上级 隐藏
     * level 2级调用getCategoryOneList 获取列表到分类上级下拉框
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {Int}       term_type   终端类型
     * @apiParam {String}    name   分类标题
     * @apiParam {Int}       level   分类层级（1级 =1 ）
     * @apiParam {Int}       pid   分类上级
     * @apiParam {String}    description   描述
     * @apiParam {String}    icon   素材地址
     * @apiParam {int}    sort   排序
     *
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */
    public function addCategory()
    {
        $info = input('param.');
        if($info['level'] == 1) {
            $info['pid'] = 0;
        }
        $data = model('Category')->checkTitleExisit($info['name'],$info['pid'],$info['term_type'],'');
        if($data) {
            $this->response('-1', '分类名称已存在!');
        }
        $res = DB::name('ArticleCategory')->insert($info);
        if ($res) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

    /**
     * @api {post} /article/Category/editCategory 编辑查看分类
     * @apiVersion              1.0.0
     * @apiName                 editCategory
     * @apiGROUP                Category
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
     *      term_type : 终端类型
     *      name : 分类标题
     *      pid : 分类上级(pid=0 分类上级隐藏)
     *      description : 描述
     *      icon : 素材地址
     *      sort ：排序
     * }
     * ]
     * }
     *
     */
    public function editCategory()
    {
        $id = input('param.id');
        $info = model('Category')->getCategoryInfo($id);
        $term_info = model('Term')->getTermTypeColumnInfo();
        $info['term_name'] = $term_info[$info['term_type']];
        if ($info) {
            $this->response('1', '获取成功', $info);
        } else {
            $this->response('-1', '暂无数据');
        }
    }

    /**
     * @api {post} /article/Category/saveCategory 提交修改分类
     * @apiVersion              1.0.0
     * @apiName                 saveCategory
     * @apiGROUP                Category
     * @apiDescription          提交修改分类
     * 分类层级 和 文章类别（type）不让修改
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {Int}       id   分类ID
     * @apiParam {Int}       term_type   终端类型
     * @apiParam {String}    name   分类标题
     * @apiParam {Int}       pid   分类上级
     * @apiParam {String}    description   描述
     * @apiParam {String}    icon   素材地址
     * @apiParam {int}       sort   排序
     *
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */

    public function saveCategory()
    {
        $info = input('param.');
        $data = model('Category')->checkTitleExisit($info['name'],$info['pid'],$info['term_type'], $info['id']);
        if($data) {
            $this->response('-1', '分类名称已存在!');
        }
        $res = DB::name('ArticleCategory')->update($info);
         if ($res !==false) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }


    /**
     * @api {post} /article/Category/deleteCategory 设置分类状态
     * @apiVersion              1.0.0
     * @apiName                 deleteCategory
     * @apiGROUP                Category
     * @apiDescription          设置分类状态
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id   分类ID
     * @apiParam {Int}          status   状态（1：正常，0：禁用）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function deleteCategory()
    {
        $id = input("param.id");
        $is_exisit = model('Category')->contentExisit($id);
        if($is_exisit) {
            $this->response('-1', '该分类下存在数据');
        }
        if(empty($id)){
            $this->response('-1', '数据不能为空');
        }

        $res = Db::name('ArticleCategory') ->where('id', 'in', $id)->delete();
        if ($res !==false) {
            $this->response('1', '删除成功');
        } else {
            $this->response('-1', '删除失败');
        }
    }
}
