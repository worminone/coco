<?php
namespace app\article\controller;

use think\Db;
use think\Request;
use app\common\controller\Admin;

class Author extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {post} /article/Author/authorList 查看当前用户笔名
     * @apiVersion              1.0.0
     * @apiName                 authorList
     * @apiGROUP                Author
     * @apiDescription          查看当前用户笔名
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       title 标题名称（可选）
     * @apiParam {Int}          user_id  用户ID   
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
     *      id:  笔名ID,
     *      user_id: 用户OD,
     *      name: 笔名,
     * }
     * ]
     * }
     *
     */
    public function authorList()
    {
        $user_id = input('param.user_id', '', 'int');
        $where['user_id'] = $user_id;

        $list = model('Author')->getAuthorList($where);
        foreach ($list as $key => $value) {
            $list[$key]['id'] = (string)$value['id'];
        }
        if (!empty($list)) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /article/Author/checkAuthorName 验证笔名是否存在(暂时作废)
     * @apiVersion              1.0.0
     * @apiName                 checkAuthorName
     * @apiGROUP                Author
     * @apiDescription          验证笔名是否存在
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {String}    name   笔名
     * @apiParam {Int}       user_id   登录用户ID
     *
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */
    public function checkAuthorName()
    {
        $name = input('param.name', '', 'htmlspecialchars');
        $user_id = input('param.user_id', '','int');
        $info = model('Author')->getAuthorName($user_id, $name);
        if (empty($info)) {
            $this->response('1', '笔名可以添加');
        } else {
            $this->response('-1', '笔名已存在');
        }
    }

    /**
     * @api {post} /article/Author/setAuthor 提交笔名
     * @apiVersion              1.0.0
     * @apiName                 setAuthor
     * @apiGROUP                Author
     * @apiDescription          提交笔名
     * @apiParam {String}    token 已登录账号的token
     * @apiParam {String}    name   笔名(多个用',' 分割)
     * @apiParam {Int}       id  笔名ID(多个用',' 分割)
     * @apiParam {Int}       user_id   登录用户user_id
     *
     * @apiSuccess {Int}     code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}  msg 成功的信息和失败的具体信息.
     *
     */
    public function setAuthor()
    {
        $info = input('param.');
        $names = explode(',', $info['name']);
        $users = explode(',', $info['id']);
        if(count($names) != count($users)) {
            $this->response('-1', '数据请求有误！');
        }
        $kuser = $users;
        $user_id = $info['user_id'];
        foreach ($kuser as $k => $v) {
            if('-1' == $v) unset($kuser[$k]);
        }
        $filter_users = array_merge($kuser);

        $user_ids = DB::name('UserAuthor')->where(['user_id'=>$user_id])->column('id');
        $user_names = DB::name('UserAuthor')->where('user_id','not in',$user_id)->column('name');
        $diff_ids = array_diff($user_ids, $filter_users);
        $renames = [];
        for ($i=0; $i < count($names); $i++) {
            if(in_array($names[$i], $user_names)) {
                $renames[] = $names[$i];
            }
            if($users[$i] != '-1') {
                $edit_author[$i]['name'] = $names[$i];
                $edit_author[$i]['id'] = $users[$i];
            } else {
                $add_author[$i]['name'] = $names[$i];
                $add_author[$i]['user_id'] = $user_id;
            }
        }
        $res1 = $res2 = $res3 = false;
        if(count($renames)>0) {
            $renames = implode(',', $renames);
            $this->response('-1', '用户名有重复', $renames);
        } else {
            if(isset($edit_author)){
                foreach ($edit_author as $key => $value) {
                    $res1 = DB::name('UserAuthor')->update($value);
                } 
            }
            if(isset($add_author)) {
                $res2 = DB::name('UserAuthor')->insertAll($add_author);
            }
            if($diff_ids) {
              $res3 = DB::name('UserAuthor')->where('id', 'in', $diff_ids)->delete();  
            }
        }

        if ($res2 !==false || $res3 !==false || $res1 !==false ) {
            $this->response('1', '你的信息已提交');
        } else {
            $this->response('-1', '信息提交失败');
        }
    }

}
