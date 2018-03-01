<?php
namespace app\article\controller;

use think\Db;
use app\common\controller\Admin;
use app\article\model\SlideShow;
use app\article\model\Common;

class Slide extends Admin
{

    public function index()
    {
        echo get_region_name(4);
    }

    /**
     * @api {get} /article/slide/slideList 轮播图列表
     * @apiVersion              1.0.0
     * @apiName                 slideList
     * @apiGroup                SlideShow
     * @apiDescription          管理员列表搜索查询
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          status 状态（可选）默认为1：正常，0：禁用
     * @apiParam {Int}          term_type 终端类型（可选）,1:高中一体机,2:校园在线APP,3:校园在线官网,4:高中学生web
     * @apiParam {Int}          category_id 分类ID（可选）
     * @apiParam {String}       keyword 搜索关键字（可选）
     * @apiParam {Int}          pagesize 每页的条目数（可选），默认为10
     * @apiParam {Int}          page 页码（可选），默认为1
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     */
    public function slideList()
    {
        $slideShow = new SlideShow();
        $where = [];
        $status = input('param.status',-1);
        if ($status !='' && $status >= 0) {
            $where['status'] = $status;
        }

        //终端类型
        $termType = input('param.term_type', -1);
        if ($termType > 0) {
            $where['term_type'] = $termType;
        }

        //分类
        $categoryId = input('param.category_id', 0);
        if ($categoryId) {
            $where['category_id'] = $categoryId;
        }
        $category_top_id = input('param.category_top_id', '-1');
        if ($category_top_id > 0 ) {
            $t_where['pid'] = $category_top_id;
            $tids = model('Category')->getCategoryTermIds($t_where);
            if (!empty($tids)) {
                $tids[] = $category_top_id;
                $where['category_id'] = ['in', $tids];
            } else {
                $where['category_id'] = $category_top_id;
            }
        }
        $keyword = input('param.keyword', '');
        if ($keyword) {
            $where['title'] = array('like','%'.$keyword.'%');
        }
        $page = input('param.page', 1, 'intval');
        $pageSize = input('param.pagesize', config('pagesize'), 'intval');
        $data = $slideShow->getList($where, $page, $pageSize);
        $this->response('1', '获取成功', $data);


    }


    /**
     * @api {get} /article/slide/view 查看轮播信息
     * @apiVersion              1.0.0
     * @apiName                 view
     * @apiGroup                SlideShow
     * @apiDescription          查看轮播信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 轮播图ID
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 数据详情
     */
    public function view()
    {
        $id = input('get.id', 0, 'intval');
        if (! $id) {
            $this->response(-1, '轮播图ID不能为空');
        }

        $info = SlideShow::get($id);
        $info['category_id'] = (string) $info['category_id'];
        $info['term_type'] = (string) $info['term_type'];
        //         aa($userInfo);
        $this->response('1', '获取成功', $info);


    }


    /**
     * @api {get} /article/slide/add 添加轮播图
     * @apiVersion              1.0.0
     * @apiName                 add
     * @apiGroup                SlideShow
     * @apiDescription          添加轮播图内容
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          term_type 终端类型,1:高中一体机,2:校园在线APP,3:校园在线官网,4:高中学生web
     * @apiParam {Int}          category_id 分类ID
     * @apiParam {String}       title 轮播图标题
     * @apiParam {String}       image_url 轮播图图片url地址
     * @apiParam {String}       jump_obj 跳转对象ID
     * @apiParam {Int}          sort 排序
     * @apiParam {String}       obj_value 跳转对象的值
     * @apiParam {String}       province 投放的省份，0是全国
     * @apiParam {String}       city 投放的城市，0是全省
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function add()
    {
        $slideShow = new SlideShow();
        $data = input('post.');
        if (! $data['category_id']) {
            $this->response(-1, '分类不能为空');
        }

        if (! $data['title']) {
            $this->response(-1, '轮播图标题不能为空');
        }

        if (! $data['term_type']) {
            $this->response(-1, '终端类型不能为空');
        }

        if (! key_exists('jump_obj', $data)) {
            $this->response(-1, '跳转类型不能为空');
        }


        //如果全选提交所有省份的话，就是全国，值为0
        $count = count(explode(',', $data['province']));
        if ($data['province'] && $count == 34 ) {
            $data['province'] = 0;
        }
        if( empty($data['province']) ) {
            $data['province'] = 0;
        }

        $data['uid'] = $this->uid;

        $id = $slideShow->addOne($data);
//         $slideShow->allowField(true)->save($data);
//         $id = $slideShow->id;
        if ($id) {
            //触发推送
            $pushModel = new Common();
            $pushModel->sendPushToR40('','update_banner');
            $this->response(1, '添加成功');
        }
    }

    /**
     * @api {get} /article/slide/update 更新轮播图内容
     * @apiVersion              1.0.0
     * @apiName                 update
     * @apiGroup                SlideShow
     * @apiDescription          更新轮播图内容
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 轮播图ID
     * @apiParam {Int}          term_type 终端类型,1:高中一体机,2:校园在线APP,3:校园在线官网,4:高中学生web
     * @apiParam {Int}          category_id 分类ID
     * @apiParam {String}       title 轮播图标题
     * @apiParam {String}       image_url 轮播图图片url地址
     * @apiParam {Int}          sort 排序
     * @apiParam {String}       jump_obj 跳转对象ID
     * @apiParam {String}       obj_value 跳转对象的值
     * @apiParam {Int}          province 投放的省份，0是全国
     * @apiParam {Int}          city 投放的城市，0是全省
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function update()
    {

        $data = input('post.');

        $id = $data['id'];
        if (! $id) {
            $this->response(-1, '轮播图ID不能为空');
        }

        if (! $data['category_id']) {
            $this->response(-1, '分类不能为空');
        }

        if (! $data['title']) {
            $this->response(-1, '轮播图标题不能为空');
        }

        if (! $data['term_type']) {
            $this->response(-1, '终端类型不能为空');
        }



        //如果全选提交所有省份的话，就是全国，值为0
        $count = count(explode(',', $data['province']));
        if ($data['province'] && $count == 34 ) {
            $data['province'] = 0;
        }

        $slideShow = new SlideShow();
        $bool = $slideShow->addOne($data);
        $s_info = $slideShow->where(['id'=>$id])->find();
        if($s_info['status'] == 1 && $s_info['term_type'] == 1) {
            model('common')->sendPushToR40('','UPDATE_BANNER');
        }
        if ($bool !== false) {
            //触发推送
            $pushModel = new Common();
            $pushModel->sendPushToR40('','update_banner');
            $this->response(1, '更新成功');
        } else {
            $this->response(-1, '更新失败');
        }
    }

    /**
     * @api {post} /article/slide/setStatus 更新轮播图状态
     * @apiVersion              1.0.0
     * @apiName                 setStatus
     * @apiGroup                SlideShow
     * @apiDescription          轮播图状态，上架和下架
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {Int}          id 轮播图ID
     * @apiParam {Int}          status 轮播图状态,1:上架,0:下架
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function setStatus()
    {
        $data = input('post.');
        $ids = explode(',', $data['id']);
        $count = count($ids);
        if (! $count) {
            $this->response(-1, '轮播图ID不能为空');
        }

        if (! key_exists('status', $data)) {
            $this->response(-1, '轮播图状态不能为空');
        }

//         aa($ids);
        //轮播图下架的时候，要看看这个轮播图是否绑定了广告，而且广告是否在有效的投放期间
        if ($data['status'] == 0) {
            foreach ($ids as $id) {
                $slideInfo = db('slide_show')->where('id='.$id)->find();
                $adId = $slideInfo['ad_id'];
                //绑定广告的才会检查
                if ($adId > 0) {
                    $adInfo = db('ad')->where('id='.$adId)->find();
                    if ($adInfo['status'] == 1 && $adInfo['release_status'] == 2) {
                        $this->response(-1, '轮播图ID为'.$id.'绑定的广告还在投放中');
                    }
                }
            }

        }

        $where = [];
        $where['id'] = ['in', $ids];
        $bool = Db::name('SlideShow')->where($where)->update(['status'=>$data['status']]);
        $info = Db::name('SlideShow')->where($where)->where(['term_type'=>1])->select();

        if ($bool && $info) {
            //触发一体机推送
            model('common')->sendPushToR40('','UPDATE_BANNER');
        }

        if ($bool !== false) {
            $this->response(1, '更新成功');
        } else {
            $this->response(-1, '更新失败');
        }
    }



}