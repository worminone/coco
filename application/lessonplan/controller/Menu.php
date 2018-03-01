<?php
namespace app\lessonplan\controller;

use think\Db;
use app\common\controller\Base;
use app\common\controller\Admin;

class Menu extends Base
{
    /**
     * @api {post} /lessonplan/Menu/getMenuList 获取目录列表
     * @apiVersion 1.0.0
     * @apiName getMenuList
     * @apiGroup Menu
     * @apiDescription 获取目录列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getMenuList()
    {
        $where=array();
        $name=trim(input('param.title', ''));
        if (strlen(trim($name))>0) {
            $where['name']=['like', '%'.$name.'%'];
            $list=model('Menu')->getMenuListByName($where);
        } else {
            $list=model('Menu')->getMenuList();
        }
        if (empty($list)) {
            $this->response(-1, '无数据');
        } else {
            foreach ($list as $key=> $val) {
                $data[$key]=array(
                    'id'       =>$val['id'],
                    'label'    =>$val['label'],
                    'children' =>$val['children'],
                    'flag'     =>false,
                    'count'    =>$val['count'],
                    'idx'      =>$key,
                );
            }
            $this->response(1, '获取成功', $data);
        }

    }
    /**
     * @api {post} /lessonplan/Menu/addFirstMenu 添加一级目录
     * @apiVersion 1.0.0
     * @apiName addFirstMenu
     * @apiGroup Menu
     * @apiDescription 添加一级目录
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} name 名称.
     *
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addFirstMenu()
    {
        $name=input('param.name', '');
        if (strlen($name)==0) {
            $data=$this->getMenuList();
            $this->response(1, '获取成功' , $data);
        }
        $save=array(
            'pid'=>0,
            'name'=>$name,
        );
        Db::name('teaching_catalogue')->insert($save);
        $data=$this->getMenuList();
        $this->response(1, '新增成功' ,$data);
    }
    /**
     * @api {post} /lessonplan/Menu/addSecondMenu 添加二级目录
     * @apiVersion 1.0.0
     * @apiName addSecondMenu
     * @apiGroup Menu
     * @apiDescription 添加二级目录
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} name 名称.
     * @apiParam {String} pid 父级ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addSecondMenu()
    {
        $data=input('param.');
        $save=array(
            'pid'=>$data['pid'],
            'name'=>$data['name'],
        );
        $id=Db::name('teaching_catalogue')->insertGetId($save);
        $update=array(
            'catalogue_id'=>$id,
        );
        Db::name('prepare_lesson')->insert($update);
        $this->response(1, '新增成功');
    }
    /**
     * @api {post} /lessonplan/Menu/updateMenu 修改标题名称
     * @apiVersion 1.0.0
     * @apiName updateMenu
     * @apiGroup Menu
     * @apiDescription 修改标题名称
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} name 名称.
     * @apiParam {String} ID 目录ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function updateMenu()
    {
        $save['name']=input('param.name');
        $where['id']=input('param.id');
        Db::name('teaching_catalogue')->where($where)->update($save);
        $this->response(1, '保存成功');
    }
    /**
 * @api {post} /lessonplan/Menu/delMenu 删除标题
 * @apiVersion 1.0.0
 * @apiName delMenu
 * @apiGroup Menu
 * @apiDescription 删除标题
 *
 * @apiParam {String} token 用户的token.
 * @apiParam {String} time 请求的当前时间戳.
 * @apiParam {String} sign 签名.
 * @apiParam {String} name 名称.
 * @apiParam {String} id 目录ID.
 *
 * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
 * @apiSuccess {String} msg 成功的信息和失败的具体信息.
 */
    public function delMenu()
    {
        $id=input('param.id');
        $flag=model('Menu')->delMenu($id);
        if ($flag==1) {
            $this->response(1, '删除成功');
        } elseif ($flag==-1) {
            $this->response(-1, '删除失败，教学内容未删除干净');
        } elseif ($flag==-3) {
            $this->response(-1, '删除失败，教案套餐有包含该目录');
        } elseif ($flag==-4) {
            $this->response(-1, '删除失败，该目录含有子目录');
        }
    }
    /**
     * @api {post} /lessonplan/Menu/teachMenu 教师端购买套餐目录
     * @apiVersion 1.0.0
     * @apiName teachMenu
     * @apiGroup Menu
     * @apiDescription 教师端购买套餐目录
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} school_id 学校id .
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function teachMenu()
    {
        $where['school_id']=input('param.school_id');
        $id=Db::name('teaching_sale')->where($where)->value('combo_id');
        if (empty($id)) {
            $this->response(-1, '学校未购买套餐');
        } else {
            $chapter=Db::name('teaching_combo')->where("id='$id'")->value('chapter_arr');
            $data = trim($chapter,',');
            $ids = explode(',',$data);
            $list = model('Menu')->getDetail($ids);
            if (empty($list)){
                $list=array();
            }
            $this->response(1, '获取成功', $list);
        }

    }
}