<?php

namespace app\user\controller;

use think\Request;
use think\Db;
use app\common\controller\Admin;

class SchoolManage extends Admin
{
    public function __construct(Request $Request)
    {
        parent::__construct($Request);
    }

    /**
     * @api {get|post} /user/SchoolManage/getStudentList 高中学校用户
     * @apiVersion 1.0.0
     * @apiName getStudentList
     * @apiGroup SchoolManage
     * @apiDescription 高中学校用户
     *
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 导师信息.
     */
    public function getStudentList()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getStudentList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /user/SchoolManage/getStudentInfo 高中学校用户详情
     * @apiVersion 1.0.0
     * @apiName getStudentInfo
     * @apiGroup SchoolManage
     * @apiDescription 高中学校用户详情
     *
     * @apiParam {String}       user_id 高中学校
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {String} data 用户信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": {
     * "school_name": "龙岩一中",//学校名称
     * "user_name": "lyyizhong",//用户名
     * "real_name": "",//联系人名字
     * "phone_tel": "",//联系电话
     * "duties": "",//在校职务
     * "auth_pic": "",//认证资料
     * "email": "lyyizhong@dadaodata.com",//email
     * "is_formal": 1,//是否是正式用户，1：正式用户，2：体验用户
     * "formal_time": 0//体验到期时间
     * "module_id":  模块ID
     * }
     * }
     */
    public function getStudentInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getStudentInfo';
        $data = curl_api($url, $param, 'post', 0);
        if ($data['data']) {
            $where['school_id'] = $data['data']['school_id'];
            $b_where['status'] = $data['data']['is_formal'];
            $module_ids = Db::name('SchoolModules')->where($where)->column('module_id');
            if (!$module_ids) {
                $module_ids = Db::name('BaseModules')->where($b_where)->column('module_id');
            }
            $data['data']['module_id'] = implode(',', $module_ids);
            $data['data']['is_formal'] = intval($data['data']['is_formal']);
        }
        echo json_encode($data);
    }

    /**
     * @api {post} /user/SchoolManage/addApply 添加学校申请
     * @apiVersion 1.0.0
     * @apiName addApply
     * @apiGroup SchoolManage
     * @apiDescription 添加学校申请
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     *
     * @apiParam {Int} user_id 绑定的用户ID(必填)
     * @apiParam {Int} school_id 对应高中学校ID
     * @apiParam {String} school_name 学校名字
     * @apiParam {Int} province_id 省ID
     * @apiParam {Int} city_id 市ID
     * @apiParam {Int} area_id 区ID
     * @apiParam {String} region_name 完整的地区名称,比如：福建省 福州市 鼓楼区
     * @apiParam {String} address 学校详细地址
     * @apiParam {String} name 申请人姓名
     * @apiParam {String} duty 申请人职务
     * @apiParam {String} phone 申请人手机号码
     * @apiParam {String} email 申请人电子邮箱
     * @apiParam {String} content 申请内容
     * @apiParam {String} upload_url 上传申请入驻的协议文档Url
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function addApply()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/addApply';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/SchoolManage/getApplyList 获取高中审核列表
     * @apiVersion              1.0.0
     * @apiName                 getApplyList
     * @apiGROUP                SchoolManage
     * @apiDescription          获取高中审核列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       keyword 关键字（可选）
     * @apiParam {Int}          is_approval 审核标志(0:等待1：通过 2：未通过）多个用","分隔
     * @apiParam {Int}          city_id 城市（可选）
     * @apiParam {String}       begin_create_time 提交开始时间（可选）
     * @apiParam {String}       end_create_time 提交结束时间（可选）
     * @apiParma {String}       admin_user 后台审核的用户ID
     * @apiParma {String}       begin_pass_time 审核开始时间
     * @apiParma {String}       end_pass_time 审核结束时间
     * @apiParam {String}       orderType 排序类型（0：提交时间 1：审核时间）可选，默认 0
     * @apiParam {String}       sort 排序（0：逆序，1：顺序）可选，默认 0
     * @apiParam {Int}          page 页码（可选），默认为1
     * @apiParam {Int}          pagesize 分页数（可选）,默认为 20
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          page_num 总页数
     *                          page 页码<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     * "count": 1,
     * "page_num": 1,
     * "page": "1",
     * "pagesize": 20,
     * "list": [
     * {
     * "id": 1,
     * "school_name": "",//学校名称
     * "region_name": "",//地址
     * "name": "",//姓名
     * "phone": "",//电话
     * "email": "",//email
     * "create_time": "2017-09-07 19:16:45",//申请时间
     * "pass_time": 0,//审核时间
     * "is_approval":1,//审核标志(0:等待审核 1：审核通过 2：审核未通过）
     * "is_approval":'审核通过',//审核名称
     * "admin_user": 4,//审核用户id
     * "admin_user_name": "zhangbensheng"//审核人
     * }
     * ]
     * }
     * }
     */
    public function getApplyList()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getApplyListV2';
        //var_dump(curl_api($url, $param, 'post',1));
        $data = curl_api($url, $param, 'post');
        if (!empty($data['data']['list'])) {
            foreach ($data['data']['list'] as &$value) {
                if (!empty($value['admin_user'])) {
                    $value['admin_user_name'] = model('AdminUser')->getNameById($value['admin_user']);
                }
            }
        }
        echo json_encode($data);
    }

    /**
     * @api {post} /user/SchoolManage/getApplyInfo 获取学校审核信息
     * @apiVersion              1.0.0
     * @apiName                 getApplyInfo
     * @apiGROUP                SchoolManage
     * @apiDescription          获取学校审核信息
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       id 审核记录id
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "查询成功",
     * "data": {
     * "id": 1,
     * "school_id": 4,//对应高中学校ID
     * "school_name": "",//学校名字
     * "province_id": 0,//省ID
     * "city_id": 0,//市ID
     * "area_id": 0,//区ID
     * "region_name": "",//完整的地区名称
     * "address": "",//学校详细地址
     * "name": "",//申请人姓名
     * "duty": "",//申请人职务
     * "phone": "",//申请人手机号码
     * "email": "",//申请人电子邮箱
     * "content": null,//申请内容
     * "create_time": "2017-09-07 19:16:45",//提交申请时间
     * "is_approval": 0,//审核标志(0:等待审核 1：审核通过 2：审核未通过）
     * "remark": "",//后台审核填写的备注
     * "user_id": 2,//绑定的用户ID
     * "upload_url": "0",//上传申请入驻的协议文档照片的url
     * "admin_user": 4,//后台审核的用户ID
     * "pass_time": 0,//审核时间
     * "admin_user_name": "zhangbensheng",//审核人
     * "is_approval_name": "等待审核"//审核状态
     * }
     * }
     */
    public function getApplyInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getApplyInfoV2';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/SchoolManage/delApplyInfo 批量删除学校审核
     * @apiVersion 1.0.0
     * @apiName delApplyInfo
     * @apiGroup SchoolManage
     * @apiDescription 批量删除学校审核
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiParam {String} id 学校审核ID(批量用","隔开)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "删除成功"
     * }
     */
    public function delApplyInfo()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/delApplyInfo';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/SchoolManage/verifyApply 审核通过
     * @apiVersion 1.0.0
     * @apiName verifyApply
     * @apiGroup SchoolManage
     * @apiDescription 审核通过
     *
     * @apiParam {String} token 用户的token.
     *
     * @apiParam {Int} id 学校审核ID
     * @apiParam {Int} admin_id 管理员ID
     * @apiParam {Int} is_create 是否创建新学校(0 否 1 是)
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "操作成功"
     * }
     */
    public function verifyApply()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/verifyApplyV2';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {post} /user/SchoolManage/refuseApply 审核拒绝
     * @apiVersion 1.0.0
     * @apiName refuseApply
     * @apiGroup SchoolManage
     * @apiDescription 审核拒绝
     *
     * @apiParam {String} token 用户的token.
     *
     * @apiParam {Int} id 学校审核ID
     * @apiParam {Int} admin_id 管理员ID
     * @apiParam {String} remark 拒绝理由
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "操作成功"
     * }
     */
    public function refuseApply()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/refuseApply';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }

    /**
     * @api {get|post} /user/SchoolManage/addUser 创建学校用户
     * @apiVersion 1.0.0
     * @apiName addUser
     * @apiGroup SchoolManage
     * @apiDescription 通过帐号（手机号/邮箱/用户名）和密码登录，成功返回token，错误返回错误信息
     * @apiParam {String} user_name 登录名(必填).
     * @apiParam {String} password 用户密码6-12位字符(必填).
     * @apiParam {String} re_password 再次输入密码(必填).
     * @apiParam {String} utype 用户类型(必填).
     * @apiParam {String} real_name 真实姓名(必填).
     * @apiParam {String} email 邮箱(选填).
     * @apiParam {Int} school_id 学校ID(选填)默认0
     * @apiParam {String} school_name 学校名称
     * @apiParam {Int} high_admin_flag 高中管理员标志(0:否 1：是）(选填)默认0
     * @apiParam {String} auditing_flag 用户审核状态，0:等待审核， 1：审核未通过 ，
     *                    2：审核通过（高校入驻申请必须是0）.
     * @apiParam {Int} is_formal 0 签约用户 1 体验用户
     * @apiParam {Int} start_time 开始时间
     * @apiParam {Int} formal_time 体验时间
     * @apiParam {Int} school_id 学校ID
     * @apiParam {String} duties 在校职务
     * @apiParam {String} auth_pic 认证资料
     * @apiParam {string} module_id 模块ID
     * @apiParam {String} reg_type 注册方式，默认手机(mobile),邮箱(email),用户名(username)
     * @apiParam {Int} auth_teacher 可以授权的教师的数量
     * @apiParam {Int} auth_student 可以授权的学生的数量
     *
     * @apiSuccess {Int} code 错误代码，1是成功,<br>
     * -20011:手机号码格式不正确,<br>
     * -20012:前后两次输入的密码不匹配,<br>
     * -20013:用户名已经注册,<br>
     * -20015:用户名用户类型不能为空注册,<br>
     * -20014:注册失败,<br>
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function addUser()
    {
        $param = input('param.');
         if( empty($param['module_id']))
         {
            $this->response('-1','未定义数组module_id');
         }
         if( empty($param['school_id']))
         {
            $this->response('-1','未定义数组school_id！');
         }
         ///流程
        //1.请求这所学校
        $id = $param['school_id'];
        //2.确认学校是否在审核列表
        $school_api = config('school_api');
        $url = $school_api . '/api/SchoolManage/getSchoolInfo';
        $schoolData = array('id' => $id);
        $school_info = curl_api($url, $schoolData, 'post');
        try {
            $enterflag = $school_info['data']['list']['0']['enterflag'];
        }
        catch (\Exception $e){
            $this->response('-1', '该校未查到入驻情况！',$school_info['data']['list']['0']['enterflag']);
        }
        //2.1在审核列表，提示当前状态
        if ($enterflag == 1) {
            $this->response('-1', '该校在审核中，不支持新增用户！');
        }
        if ($enterflag == 2) {
            $this->response('-1', '该校已审核成功，不支持新增用户！');
        }
         $param['auditing_flag'] = 0;
        //2.2不在审核列表，恢复当前新增业务

        $base_api = config('base_api');
        $url = $base_api . '/api/user/addUser';
        $data = curl_api($url, $param, 'post');
        if (!empty($data['code'])) {
            if ($data['code'] == 1) {
                //修改学校状态为入驻
                $school_api = config('school_api');
                $url = $school_api . '/api/SchoolManage/addInfoV2';
                $school_id = input('school_id', '');
                $schoolData = array(
                    'school_id' => $school_id,
                    'enterflag' => 1,
                    'enter_time' => time(),
                    'real_name'=> input('real_name',''),
                    'auth_pic'=> input('auth_pic',''),
                    'phone_tel'=> input('phone_tel',''),
                    'user_id'=> $data['data'],
                    'auth_teacher' => input('auth_teacher'),
                    'auth_student' => input('auth_student'),
                );
                curl_api($url, $schoolData, 'post');
                $module_ids = explode(',', $param['module_id']);
                $m_info = [];
                Db::name('SchoolModules')->where(['school_id'=>$param['school_id']])->delete();
                foreach ($module_ids as $key => $value) {
                    $m_info[$key]['school_id'] = $param['school_id'];
                    $m_info[$key]['module_id'] = $value;
                }
                $m_info = array_values($m_info);
                Db::name('SchoolModules')->insertAll($m_info);
            }
            //需求更新：推送新增成功
            // $base_api = config('ddzx_api');
            // $url = $base_api . '/message/index/addMessage';
            // $params = [
            //     'type' => 2,

            // ];
            // curl_api($url, $params, 'post');
        }
        echo json_encode($data);
    }

    /**
     * @api {get|post} /user/SchoolManage/editUser 编辑学校用户
     * @apiVersion 1.0.0
     * @apiName editUser
     * @apiGroup SchoolManage
     * @apiParam {Int} user_id 用户Id
     * @apiParam {String} password 用户密码6-12位字符(必填).
     * @apiParam {String} utype 用户类型.
     * @apiParam {String} real_name 真实姓名.
     * @apiParam {String} email 邮箱(选填).
     * @apiParam {Int} college_id 大学ID(选填)默认0
     * @apiParam {Int} high_admin_flag 高中管理员标志(0:否 1：是）(选填)默认0
     * @apiParam {String} auditing_flag 用户审核状态，0:等待审核， 1：审核未通过 ，
     *                    2：审核通过（高校入驻申请必须是0）.
     * @apiParam {Int} is_formal 0 签约用户 1 体验用户
     * @apiParam {Int} start_time 开始时间
     * @apiParam {Int} formal_time 体验时间
     * @apiParam {string} module_id 模块ID
     * @apiParam {Int} school_id 学校ID
     * @apiParam {String} duties 在校职务
     * @apiParam {String} auth_pic 认证资料
     * @apiParam {Int} auth_teacher 可以授权的教师的数量
     * @apiParam {Int} auth_student 可以授权的学生的数量
     *
     * @apiSuccess {Int} code 错误代码，1是成功,<br>
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object} data
     */
    public function editUser()
    {
        $param = input('param.');
        $base_api = config('base_api');
        $url = $base_api . '/api/user/adminUpdateUserData';
        $data = curl_api($url, $param, 'post');
        $school_api = config('school_api');
        $param1 = [];
        $param1['id'] = $param['school_id'];
        $param1['contact_man'] = $param['real_name'];
        $param1['contact_tel'] = $param['phone_tel'];
        $param1['auth_teacher'] = $param['auth_teacher'];
        $param1['auth_student'] = $param['auth_student'];

//         aa($param1);
        $url1 = $school_api . '/api/SchoolManage/saveSchoolInfo';
        curl_api($url1, $param1, 'post');
        if (!empty($data['code'])) {
            if ($data['code'] == 1) {
                $module_ids = explode(',', $param['module_id']);
                $m_info = [];
                foreach ($module_ids as $key => $value) {
                    $m_info[$key]['school_id'] = $param['school_id'];
                    $m_info[$key]['module_id'] = $value;
                }
                $m_info = array_values($m_info);
                Db::name('SchoolModules')->where(['school_id' => $param['school_id']])->delete();
                Db::name('SchoolModules')->insertAll($m_info);
            }
        }

        echo json_encode($data);
    }

    /**
     * @api {get|post} /user/SchoolManage/getList 获取高中学校列表
     * @apiVersion              1.0.0
     * @apiName                 getList
     * @apiGROUP                SchoolManage
     * @apiDescription          获取高中学校列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       keyword 关键字（可选）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     count 数据总条数<br>
     *                          page_num 总页数
     *                          page 页码<br>
     *                          pagesize 每页数据条数<br>
     *                          data 数据详情<br>
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     * "count": 2,
     * "page_num": 1,
     * "page": "1",
     * "pagesize": 20,
     * "list": [
     * {
     * "school_id": 1,//学校ID
     * "school_name": "1",//学校名称
     * },
     * ]
     * }
     * }
     */
    public function getList()
    {
        $param = input('param.');
        $school_api = config('school_api');
        $param['page'] = 1;
        $param['pagesize'] = 200;
        $param['field'] = 'school_id,school_name';
        $url = $school_api . '/api/SchoolManage/getList';
        $data = curl_api($url, $param, 'post');
        echo json_encode($data);
    }


    /**
     * @api {get|post} /user/SchoolManage/getModulesList 获取套餐模块列表
     * @apiVersion              1.0.0
     * @apiName                 getModulesList
     * @apiGROUP                SchoolManage
     * @apiDescription          获取套餐模块列表
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     * {
     *          id: "id",
     *          title: "模块名称"
     *
     * }
     * }
     */
    public function getModulesList()
    {
        $list = Db::name('Modules')->field('id,title')->select();
        foreach ($list as $key => $value) {
            $list[$key]['id'] = (string)$value['id'];
        }
        $this->response('1', '操作成功', $list);
    }

    /**
     * @api {get|post} /user/SchoolManage/getBaseModulesList 获取基础套餐模块列表
     * @apiVersion              1.0.0
     * @apiName                 getBaseModulesList
     * @apiGROUP                SchoolManage
     * @apiDescription          获取基础套餐模块列表
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       status  （1.签约账户 2.体验账户）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     * {
     *          module_id: "模块ID",
     *
     * }
     * }
     */
    public function getBaseModulesList()
    {
        $status = input('param.status', '1', 'intval');
        $where['status'] = $status;
        $list = Db::name('BaseModules')->where($where)->column('module_id');
        $this->response('1', '操作成功', $list);
    }

    /**
     * @api {get|post} /user/SchoolManage/saveBaseModules 修改基础套餐模块
     * @apiVersion              1.0.0
     * @apiName                 saveBaseModules
     * @apiGROUP                SchoolManage
     * @apiDescription          修改基础套餐模块
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       module_id  模块ID（多个','分割）
     * @apiParam {Int}          status  （1.签约账户 2.体验账户）
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     */
    public function saveBaseModules()
    {
        $param = input('param.');
        $module_map = explode(',', $param['module_id']);
        $status = $param['status'];
        $data = [];
        foreach ($module_map as $key => $value) {
            $data[$key]['module_id'] = $value;
            $data[$key]['status'] = $status;
        }
        Db::name('BaseModules')->where(['status' => $status])->delete();
        $data = array_values($data);
        $res = Db::name('BaseModules')->insertAll($data);
        if ($res) {
            $this->response('1', '操作成功');
        } else {
            $this->response('-1', '操作失败');
        }
    }

    /**
     * @api {get|post} /user/SchoolManage/getBaseModules 获取学校套餐模块列表
     * @apiVersion              1.0.0
     * @apiName                 getBaseModules
     * @apiGROUP                SchoolManage
     * @apiDescription          获取学校套餐模块列表
     * @apiParam {String}       token 已登录账号的token
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": "1",
     * "msg": "获取成功",
     * "data": {
     * {
     *    id: "id",
     *    module_name: "模块名称"
     *    school_id: 学校ID
     *    school_id :模块ID
     *
     * }
     * }
     */
    public function getBaseModules()
    {
        $school_id = input('param.school_id', '', 'intval');
        $list = Db::name('SchoolModules')->where(['school_id'=>$school_id])->select();
        foreach ($list as $key=>$value) {
            $info = Db::name('Modules')->where(['id'=>$value['module_id']])->find();
            $list[$key]['module_name'] = $info['title'];
        }
        $this->response('1', '操作成功', $list);
    }
}
