<?php
namespace app\api\controller\school;

use think\Db;
use think\Request;
use app\common\controller\Api;
use app\message\model\Message;

class Feedback extends Api
{
    /**
     * @api {post} /api/school.Feedback/getSchoolType 获取问题类型
     * @apiVersion              1.0.0
     * @apiName                 getSchoolType
     * @apiGROUP                Feedback
     * @apiDescription          获取问题类型
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function getSchoolType(){
        $model = new Message();
        $info = $model->getSchoolFeedback();
        $this->response('1', '获取成功', $info);
    }
    /**
     * @api {post} /api/school.Feedback/FeedbackSubmit 学校问题提交
     * @apiVersion              1.0.0
     * @apiName                 FeedbackSubmit
     * @apiGROUP                Feedback
     * @apiDescription          学校问题提交
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       type 类型ID
     * @apiParam {String}       content 内容
     * @apiParam {String}       pic_url 图片地址（用,分隔）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function FeedbackSubmit()
    {
        $url = config('base_api').'/api/user/getData';
        $headers = Request::instance()->header();
        $this->token = key_exists('token', $headers) ? $headers['token'] : '';
        $param['token'] = $this->token ?: input('param.token');
        $data = curl_api($url, $param, 'post');
        if(empty($data['data'])){
            $this->response(-99999, '无效账户');
        }
        $save['term_type'] = 4;
        $save['type'] = input('param.type', 1);
        $save['describe'] = input('param.content', '');
        $save['pic_url'] = input('param.pic_url', '');
        $save['school_name'] = $data['data']['school_name'];
        $save['contact'] = $data['data']['real_name'];
        $save['telphone'] = $data['data']['phone_tel'];
        $id = Db::name('feedback')->insertGetId($save);
        if($id>0){
            $messageModel = new Message();
            $messageModel->addInfo('6', $id, $save['describe'], $save['telphone']);
            $this->response(1, '提交成功，我们会第一时间查看您的反馈', $id);
        } else {
            $this->response(-1, '提交失败');
        }
    }
}

