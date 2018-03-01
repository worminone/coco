<?php
namespace app\api\controller\wechat;

use think\Db;
use app\common\controller\Api;
use app\article\model\Journal;

class WechatTopic extends Api
{

    /**
     * @api {post} /Api/wechat.WechatTopic/journalCoverList 微信端校刊列表
     * @apiVersion              1.0.0
     * @apiName                 journalCoverList
     * @apiGROUP                APi
     * @apiDescription          校刊列表
     * @apiParam {String}       token 已登录账号的token.
     * @apiParam {int}          page 当前页.
     * @apiParam {int}          pagesize 分页数，默认是10.
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
        $pagesize = input('param.pagesize', '10', 'int');
        $category_id = input('param.category_id', '0');
        $page = input('param.page', '1', 'int');
        if($pagesize <= 0 || $page <= 0) {
            $this->response('-1', '分页数或当前页必须大于零');
        }
        $where['term_type'] = 6;
        $where['status'] = 1;
        if (!empty($category_id))
        {
            $where['category_id'] = ['in', $category_id];
        }
        $field = '*';
        $limit = $pagesize*($page-1).','.$pagesize;
        $list = $journal->getJournalCoverList($where, $field, $limit, ['is_top'=>'desc','sort'=>'desc', 'id'=>'desc']);
        $author_id = '';
        foreach ($list as $k => $v) {
            $author_id .= ',' . $v['author_id'];
        }
        $author_list = Db::name('adminUser')
        ->where(['id'=>['in', $author_id]])
        ->column('id,true_name');
        $author_name = [];
        $author = [];
        foreach ($list as $key => $value) {
            $author[$key] = explode(',',$value['author_id']);
            foreach ($author[$key] as $ke => $val) {
                $author_name[$key][$ke] = empty($author_list[$val])? '匿名' :$author_list[$val];
            }
            $list[$key]['index_img'] =  $list[$key]['index_img'] .'?imageView2/1/q/80/&imageslim';
            $list[$key]['list_img'] = $list[$key]['list_img'] . '?imageView2/1/q/80/&imageslim';
            $list[$key]['author_name'] = implode('　', $author_name[$key]);
            $list[$key]['create_time'] = empty(explode(' ',$list[$key]['create_time'])[0]) ? $list[$key]['create_time'] : explode(' ',$list[$key]['create_time'])[0];
        }
        if ($list) {
            $this->response('1', '获取成功', $list);
        } else {
            $this->response('-1', '未查询到数据', $list);
        }
    }

    /**
     * @api {post} /Api/wechat.WechatTopic/journalInfo 微信端期刊详情
     * @apiVersion              1.0.0
     * @apiName                 journalInfo
     * @apiGROUP                APi
     * @apiDescription          微信端期刊详情
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
        $url_list['img'] = empty($info['content_url'])? [] :explode(',', $info['content_url']);
        foreach ($url_list['img'] as $k => $v){
            $url_list['img'][$k] = $v . '?imageView2/1/q/80/&imageslim';
        }
        $this->response('1', '获取成功', $url_list);
    }
}

