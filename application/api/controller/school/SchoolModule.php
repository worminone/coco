<?php
namespace app\api\controller\school;

use think\Db;
use app\common\controller\Api;

class SchoolModule extends Api
{
    /**
     * @api {post} /api/school.SchoolModule/getSchoolModule 获取高中端模块权限列表
     * @apiVersion 1.0.0
     * @apiName getSchoolModule
     * @apiGroup College
     * @apiDescription 获取高中端模块权限列表
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {string} school_id 学校ID.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *    id: 1,
     *    school_id: 学校ID,
     *    module_id: 模块ID,
     *    module_name: "模块名称"
     * }
     * ]
     * }
     */

    public function getSchoolModule()
    {
        $school_id = input('param.school_id');
        $list = DB::name('SchoolModules')->where(['school_id'=>$school_id])->select();
        $info = DB::name('Modules')->column('title','id');
        foreach ($list as $key=>$value) {
            $list[$key]['module_name'] = $info[$value['module_id']];
        }
        $this->response('1', '获取成功', $list);
    }
}

