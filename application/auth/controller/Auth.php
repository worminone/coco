<?php

namespace app\auth\controller;

use app\common\controller\Admin;
use think\Request;
use think\Cache;

class Auth extends Admin
{
    private $menu;

    public function __construct(Request $Request)
    {
        parent::__construct($Request);
        $this->setMenu();
    }


    private function setMenu()
    {
        $menu = [];
        //数据一一对应menu表字段('id', 'parent_id', 'url', 'type', 'status', 'name');
        //首页---index
        $menu[] = array(10, 0, 'index', 0, 1, '首页', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(15, 10, 'index/home/statistics', 1, 1, '统计概览');

        //数据---data
        $menu[] = array(20, 0, 'data', 0, 1, '数据', 'http://www.zgxyzx.net/images/job-ico.png');

        $menu[] = array(26, 20, 'data/SchoolManage/getList', 1, 1, '高中学校数据库');
        $menu[] = array(27, 26, 'data/SchoolManage/getInfo', 2, 1, '基本数据查看');
        $menu[] = array(28, 26, 'data/SchoolManage/addInfo', 2, 1, '基本数据编辑');

        $menu[] = array(29, 20, 'data/TeacherManage/updateInfo', 1, 1, '全国老师数据库');
        $menu[] = array(30, 29, 'data/TeacherManage/getInfo', 2, 1, '基本数据查看');
        $menu[] = array(31, 29, 'data/TeacherManage/updateInfo', 2, 1, '在校信息查看');

        $menu[] = array(32, 20, 'data/StudentManage/getBaseList', 1, 1, '全国学生数据库');
        $menu[] = array(33, 32, 'data/StudentManage/getInfo', 2, 1, '档案详情');
        $menu[] = array(34, 32, 'data/StudentManage/getStuParentInfo', 2, 1, '家庭信息');

        $menu[] = array(35, 20, 'data/ParentManage/getList', 1, 1, '全国家长数据库');

        $menu[] = array(50, 20, 'data/Major/majorList', 1, 1, '大学专业库数据');
        $menu[] = array(51, 50, 'data/Major/addMajor', 2, 1, '本地专业添加');
        $menu[] = array(52, 50, 'data/Major/editMajor', 2, 1, '本地专业查看');
        $menu[] = array(53, 50, 'data/Major/saveMajor', 2, 1, '本地专业编辑');
        $menu[] = array(54, 50, 'data/Major/deleteMajor', 2, 1, '本地专业删除');
        $menu[] = array(55, 50, 'data/Major/collegeListByNumber', 2, 1, '关联院校');

        $menu[] = array(56, 50, 'data/Major/majorTopList', 2, 1, '学科门类列表');
        $menu[] = array(57, 50, 'data/Major/majorTypeList', 2, 1, '学科类别列表');
        $menu[] = array(58, 50, 'data/Major/addMajorTop', 2, 1, '学科门类或类别新增');
        $menu[] = array(59, 50, 'data/Major/editMajorTop', 2, 1, '学科门类或类别查看');
        $menu[] = array(60, 50, 'data/Major/saveMajorTop', 2, 1, '学科门类或类别修改');
        $menu[] = array(61, 50, 'data/Major/deleteMajorTop', 2, 1, '学科门类或类别删除');

        $menu[] = array(62, 20, 'data/Major/subjectList', 1, 1, '学科库数据');

        $menu[] = array(65, 20, 'data/Occupation/occupationList', 1, 1, '职业库数据');
        $menu[] = array(66, 65, 'data/Occupation/addOccupation', 2, 1, '职业新增');
        $menu[] = array(67, 65, 'data/Occupation/editOccupation', 2, 1, '职业查看');
        $menu[] = array(68, 65, 'data/Occupation/saveOccupation', 2, 1, '职业修改');
        $menu[] = array(69, 65, 'data/Occupation/deleteOccupation', 2, 1, '职业删除');

        $menu[] = array(70, 65, 'data/Occupation/occupationTypeList', 2, 1, '职业类型管理');
        $menu[] = array(71, 65, 'data/Occupation/addOccupationType', 2, 1, '职业类型新增');
        $menu[] = array(72, 65, 'data/Occupation/editOccupationType', 2, 1, '职业类型查看');
        $menu[] = array(73, 65, 'data/Occupation/saveOccupationType', 2, 1, '职业类型修改');
        $menu[] = array(74, 65, 'data/Occupation/deleteOccupationType', 2, 1, '职业类型删除');

        $menu[] = array(80, 20, 'data/Industry/industryList', 1, 1, '行业库数据');
        $menu[] = array(81, 80, 'data/Industry/addIndustry', 2, 1, '行业新增');
        $menu[] = array(82, 80, 'data/Industry/editIndustry', 2, 1, '行业查看');
        $menu[] = array(83, 80, 'data/Industry/saveIndustry', 2, 1, '行业修改');
        $menu[] = array(84, 80, 'data/Industry/deleteIndustry', 2, 1, '行业删除');

        //用户---user
        $menu[] = array(90, 0, 'user', 0, 1, '用户', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(91, 90, 'user/school_aio/index', 1, 1, '高中一体机用户管理');
        $menu[] = array(92, 91, 'user/school_aio/view', 2, 1, '高中一体机用户数据查看');
        $menu[] = array(93, 91, 'user/school_aio/edit', 2, 1, '高中一体机用户数据更新');
        $menu[] = array(94, 91, 'user/school_aio/add', 2, 1, '高中一体机用户数据添加');

        $menu[] = array(95, 90, 'user/UserManage/getList', 1, 1, '中学管理用户管理');
        $menu[] = array(96, 95, 'user/SchoolManage/getStudentInfo', 2, 1, '中学管理用户数据查看');
        $menu[] = array(97, 95, 'user/SchoolManage/eidtUser', 2, 1, '中学管理用户数据更新');
        $menu[] = array(98, 95, 'user/SchoolManage/addUser', 2, 1, '中学管理用户数据添加');


        $menu[] = array(99, 90, 'user/UserManage/getList', 1, 1, '中学老师用户管理');
        $menu[] = array(100, 99, 'user/TeacherManage/getInfo', 2, 1, '中学老师用户数据查看');
        $menu[] = array(101, 99, 'user/TeacherManage/updateInfo', 2, 1, '中学老师用户数据更新');

        $menu[] = array(102, 90, 'user/UserManage/getList', 1, 1, '中学学生用户管理');
        $menu[] = array(103, 102, 'user/StudentManage/getInfo', 2, 1, '中学学生用户数据查看');
        $menu[] = array(104, 102, 'user/StudentManage/updateInfo', 2, 1, '中学学生用户数据更新');

        $menu[] = array(105, 90, 'user/UserManage/getList', 1, 1, '中学家长用户管理');
        $menu[] = array(106, 105, 'user/ParentManage/updateInfo', 2, 1, '中学家长用户数据查看');
        $menu[] = array(107, 105, 'user/ParentManage/getInfo', 2, 1, '中学家长用户数据更新');

        $menu[] = array(108, 90, 'user/UserManage/getList', 1, 1, '院校管理用户管理');
        $menu[] = array(109, 108, 'user/college/getUserInfo', 2, 1, '院校管理用户数据查看');
        $menu[] = array(110, 108, 'user/college/editUser', 2, 1, '院校管理用户数据更新');
        $menu[] = array(111, 108, 'user/college/addUser', 2, 1, '院校管理用户数据添加');

        $menu[] = array(112, 90, 'user/SchoolManage/getApplyList', 1, 1, '高中审核列表');
        $menu[] = array(113, 112, 'user/SchoolManage/getApplyInfo', 2, 1, '审核详情');
        $menu[] = array(117, 112, 'user/SchoolManage/verifyApply', 2, 1, '高中账号通过审核');
        $menu[] = array(118, 112, 'user/SchoolManage/refuseApply', 2, 1, '高中账号拒绝审核');

        $menu[] = array(114, 90, 'user/college/collegeRegisterschList', 1, 1, '高校审核列表');
        $menu[] = array(115, 114, 'user/college/getCollegeRegisterschInfo', 2, 1, '高校审核详情');
        $menu[] = array(116, 114, 'user/college/verifyRegistersch', 2, 1, '审核高校入驻信息');

        //权限---auth
        $menu[] = array(120, 0, 'auth', 0, 1, '权限', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(121, 120, 'auth/user/index', 1, 1, '管理员用户列表');
        $menu[] = array(122, 121, 'auth/user/view', 2, 1, '管理员资料查看');
        $menu[] = array(123, 121, 'auth/user/add', 2, 1, '管理员用户数据添加');
        $menu[] = array(124, 121, 'auth/user/edit', 2, 1, '管理员用户数据更新');

        $menu[] = array(128, 120, 'auth/group/index', 1, 1, '部门管理');
        $menu[] = array(129, 128, 'auth/group/view', 2, 1, '部门资料查看');
        $menu[] = array(130, 128, 'auth/group/add', 2, 1, '部门数据添加');
        $menu[] = array(131, 128, 'auth/group/edit', 2, 1, '部门数据更新');

        $menu[] = array(135, 120, 'auth/role/index', 1, 1, '用户角色管理');
        $menu[] = array(136, 135, 'auth/role/view', 2, 1, '用户角色资料查看');
        $menu[] = array(137, 135, 'auth/role/add', 2, 1, '用户角色添加');
        $menu[] = array(138, 135, 'auth/role/edit', 2, 1, '用户角色对应权限编辑');
        $menu[] = array(139, 135, 'auth/role/user', 2, 1, '管理员角色绑定');
        $menu[] = array(140, 135, 'auth/role/menuList', 2, 1, '获取所有菜单节点数据');
        $menu[] = array(141, 135, 'auth/role/roleAuth', 2, 1, '给角色分配权限');
        $menu[] = array(142, 135, 'auth/role/userRole', 2, 1, '给管理员分配角色');


        //信息化教案管理---lessonplan
        $menu[] = array(150, 0, 'lessonplan', 0, 1, '教案信息化管理', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(151, 150, 'lessonplan/Combo/getComboList', 1, 1, '教案套餐管理');
        $menu[] = array(152, 150, 'lessonplan/HomeworkManage/getList', 1, 1, '学生作业管理');
        $menu[] = array(153, 150, 'lessonplan/Menu/getMenuList', 1, 1, '教案目录管理');
        $menu[] = array(154, 150, 'lessonplan/TchPrepare/prepareList', 1, 1, '备课管理列表');
        $menu[] = array(155, 150, 'lessonplan/TeachingManage/getList', 1, 1, '教案管理列表');
        $menu[] = array(156, 150, 'lessonplan/Video/getVideoList', 1, 1, '视频管理列表');

        //信息化教案管理:教案套餐管理-----lessonplan:Combo
        $menu[] = array(160, 151, 'lessonplan/Combo/getComboDetail', 2, 1, '获取套餐细节');      //（查看）
        $menu[] = array(161, 151, 'lessonplan/Combo/getComboData', 2, 1, '获取套餐数据');         //（修改）
        $menu[] = array(162, 151, 'lessonplan/Combo/delComboData', 2, 1, '删除套餐数据');
        $menu[] = array(163, 151, 'lessonplan/Combo/updateComboData', 2, 1, '新增/修改套餐数据');

        //信息化教案管理:教案套餐管理-----lessonplan:Combo



        //内容管理----article
        $menu[] = array(200, 0, 'article', 0, 1, '内容', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(201, 200, 'article/Article/articleList', 1, 1, '公共文章库管理');
        $menu[] = array(202, 201, 'article/Article/addArticle', 2, 1, '公共文章库新增');
        $menu[] = array(203, 201, 'article/Article/editArticle', 2, 1, '公共文章库查看');
        $menu[] = array(204, 201, 'article/Article/saveArticle', 2, 1, '公共文章库修改');

        $menu[] = array(210, 200, 'article/Term/termList', 1, 1, '文章内容管理');
        $menu[] = array(211, 210, 'article/Term/addTerm', 2, 1, '文章内容新增');
        $menu[] = array(212, 210, 'article/Term/editTerm', 2, 1, '文章内容查看');
        $menu[] = array(213, 210, 'article/Term/saveTopic', 2, 1, '文章内容修改');
        $menu[] = array(214, 210, 'article/Term/deleteTopic', 2, 1, '文章内容上下架');

        $menu[] = array(220, 200, 'article/Topic/topicList', 1, 1, '专题内容管理');
        $menu[] = array(221, 220, 'article/Topic/addTopic', 2, 1, '专题内容新增');
        $menu[] = array(222, 220, 'article/Topic/editTopic', 2, 1, '专题内容查看');
        $menu[] = array(223, 220, 'article/Topic/saveTopic', 2, 1, '专题内容修改');
        $menu[] = array(224, 220, 'article/Topic/deleteTopic', 2, 1, '专题内容上下架');

        $menu[] = array(230, 200, 'article/Slide/slideList', 1, 1, '轮播图片管理');
        $menu[] = array(231, 230, 'article/Slide/add', 2, 1, '轮播图片新增');
        $menu[] = array(232, 230, 'article/Slide/view', 2, 1, '轮播图片查看');
        $menu[] = array(233, 230, 'article/Slide/update', 2, 1, '轮播图片修改');
        $menu[] = array(234, 230, 'article/Slide/setStatus', 2, 1, '轮播图片上下架');

        $menu[] = array(240, 200, 'article/Video/videoList', 1, 1, '视频内容管理');
        $menu[] = array(241, 240, 'article/Video/addVideo', 2, 1, '视频内容新增');
        $menu[] = array(242, 240, 'article/Video/editVideo', 2, 1, '视频内容查看');
        $menu[] = array(243, 240, 'article/Video/saveVideo', 2, 1, '视频内容修改');
        $menu[] = array(244, 240, 'article/Video/deleteVideo', 2, 1, '视频内容上下架');

        $menu[] = array(250, 200, 'article/Journal/journalList', 1, 1, '期刊内容管理');
        $menu[] = array(251, 250, 'article/Journal/addJournal', 2, 1, '期刊内容新增');
        $menu[] = array(252, 250, 'article/Journal/editJournal', 2, 1, '期刊内容查看');
        $menu[] = array(253, 250, 'article/Journal/saveJournal', 2, 1, '期刊内容修改');
        $menu[] = array(254, 250, 'article/Journal/deleteJournal', 2, 1, '期刊内容上下架');


        //系统----system
        $menu[] = array(300, 0, 'system', 0, 1, '系统', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(301, 300, 'article/Category/categoryList', 1, 1, '分类管理');
        $menu[] = array(302, 301, 'article/Category/addCategory', 2, 1, '分类新增');
        $menu[] = array(303, 301, 'article/Category/editCategory', 2, 1, '分类查看');
        $menu[] = array(304, 301, 'article/Category/saveCategory', 2, 1, '分类修改');
        $menu[] = array(305, 250, 'article/Category/deleteCategory', 2, 1, '分类上下架');

        $menu[] = array(310, 300, 'system/SystemManage/getInfo', 1, 1, '校园在线官网配置');
        $menu[] = array(311, 310, 'system/SystemManage/updateInfo', 2, 1, '修改配置');

        $menu[] = array(315, 300, 'system/SystemManage/HomeCoverList', 1, 1, '高中升学一体机');
        $menu[] = array(316, 315, 'system/SystemManage/saveHomeCover', 2, 1, '修改高中升学一体机信息');

        //审核----examine
        $menu[] = array(350, 0, 'examine', 0, 1, '审核', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(351, 350, 'examine/Examine/redisCollegeList', 1, 1, '院校审核管理');
        $menu[] = array(352, 351, 'examine/Examine/redisCollegeInfo', 2, 1, '院校审核信息');
        $menu[] = array(353, 351, 'examine/Examine/saveRedisCollegeSubmit', 2, 1, '提交院校审核');

        $menu[] = array(355, 350, 'examine/Examine/redisCollegeVideoList', 1, 1, '视频审核管理');
        $menu[] = array(356, 355, 'examine/Examine/redisCollegeVideoInfo', 2, 1, '视频审核信息');
        $menu[] = array(357, 355, 'examine/Examine/addRedisCollegeVideoSubmit', 2, 1, '提交视频审核');

        $menu[] = array(360, 350, 'examine/Examine/redisCollegePicList', 1, 1, '图片审核管理');
        $menu[] = array(361, 360, 'examine/Examine/redisCollegePicInfo', 2, 1, '图片审核信息');
        $menu[] = array(362, 360, 'examine/Examine/addRedisCollegePicSubmit', 2, 1, '提交图片审核');


        //广告----ad
        $menu[] = array(380, 0, 'ad', 0, 1, '广告', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(381, 380, 'ad/Index/getList', 1, 1, '广告列表');
        $menu[] = array(382, 381, 'ad/Index/adAdd', 2, 1, '添加广告');
        $menu[] = array(383, 381, 'ad/Index/adUpdate', 2, 1, '修改广告');
        $menu[] = array(384, 381, 'ad/Index/view', 2, 1, '获取单个广告信息');
        $menu[] = array(385, 381, 'ad/Index/setStatus', 2, 1, '广告的上架下架');
        $menu[] = array(386, 381, 'ad/Config/setConfig', 2, 1, '配置广告投放数量');
        $menu[] = array(387, 381, 'ad/Index/fromTime', 2, 1, '广告的的时间排期查看');
        $menu[] = array(388, 381, 'ad/Index/fromRegion', 2, 1, '广告的的地区排期查看');
        $menu[] = array(389, 381, 'ad/Index/statistics', 2, 1, '广告的统计数据查看');


        //消息----message
        $menu[] = array(400, 0, 'message', 0, 1, '消息', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(401, 400, 'message/Index/getList', 1, 1, '消息列表');
        $menu[] = array(402, 401, 'message/Index/view', 2, 1, '查看单挑消息');
        $menu[] = array(403, 401, 'message/Index/setRead', 2, 1, '设置已读');
        $menu[] = array(404, 401, 'message/Index/cout', 2, 1, '统计所有未读条数');


        //招商----business
        $menu[] = array(410, 0, 'business', 0, 1, '招商', 'http://www.zgxyzx.net/images/job-ico.png');
        $menu[] = array(411, 410, 'business/BusinessManage/getList', 1, 1, '招商列表');
        $this->menu = $menu;
    }

    //菜单初始化
    public function initMenu()
    {
        if ($this->uid !== 1) {
            $this->response('-1', '不是超级管理员，禁止操作');
        }

        foreach ($this->menu as $one) {
            //             aa($one);
            $data = array();
            $data['id'] = $one[0];
            $data['parent_id'] = $one[1];
            $data['url'] = $one[2];
            $data['type'] = $one[3];
            $data['status'] = $one[4];
            $data['name'] = $one[5];
            $data['icon'] = key_exists('6', $one) ? $one[6] : '';
            db('menu')->insert($data, true);
        }

        //清楚缓存
        $key = 'menu_list';
        Cache::set($key, '');
        echo '操作成功';
        exit;
    }

    /**
     * @api {get} /auth/auth/getMenu  前端的左侧菜单列表
     * @apiVersion              1.0.0
     * @apiName                 getMenu
     * @apiGROUP                ROLE
     * @apiDescription          前端页面显示的1、2级的菜单数据
     * @apiParam {Int}          cache 是否读取缓存,1:读取缓存，0：读取数据库，默认是1
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccess {Object}     data 返回的数据列表.
     */
    public function getMenu()
    {
        $cache = input('cache', 1, 'intval');

        if (config('my_env') == 'dev' || !$cache) {
            $key = rand_string();
        } else {
            $key = 'menu_list_front_' . $this->uid;
        }

        $key = uniqid();
        $data = Cache::get($key);
        if (!$data) {

            //超级管理员显示所有层级的菜单
            if ($this->uid !== 1) {
                $roleIds = db('admin_user')->where('id=' . $this->uid)->value('role_id');

                $roleIds = trim($roleIds, ',');
                //获取该用户所有角色对应的菜单节点值
                $menuIdsArr = db('role')->where("id in ($roleIds)")->column('menu_id');
                $menuIds = implode(',', $menuIdsArr);

                $where = [];
                $where['status'] = 1;
                $where['type'] = array('in', [0, 1]);
            }
            $srcData = db('menu')->field('id, parent_id,name, icon, url as path')->where($where)->where('id','in',$menuIds)->select();

//             foreach ($srcData as &$one) {
//                 $one['path'] = $one['url'];
//             }

            $BuildTreeArray = new \BuildTreeArray($srcData, 'id', 'parent_id', 0);
            $data = $BuildTreeArray->getChildren(0);

            Cache::set($key, $data);
        }

        $this->response(1, '获取成功', $data);
    }
}
