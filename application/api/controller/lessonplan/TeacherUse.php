<?php

namespace app\api\controller\lessonplan;

use think\Db;
use app\common\controller\Api;
use app\lessonplan\model\Menu;
use app\lessonplan\model\Homework;
use app\lessonplan\model\TeachingContent;

class TeacherUse extends Api
{
    protected $content_type = array(
        1 => '视频',
        2 => '测评',
        3 => '作业',
        4 => '文档',
        5 => '文章'
    );

    public function teachMenu()
    {
        $where['school_id'] = input('param.school_id');
        $id = Db::name('teaching_sale')->where($where)->value('combo_id');
        if (empty($id)) {
            $this->response(-1, '学校未购买套餐');
        } else {
            $chapter = Db::name('teaching_combo')->where("id='$id'")->value('chapter_arr');
            $data = trim($chapter, ',');
            $ids = explode(',', $data);
            $model = new Menu();
            $list = $model->getDetail($ids);
            if (empty($list)) {
                $list = array();
            }
            $this->response(1, '获取成功', $list);
        }
    }

    public function prepareMain()
    {
        $param['id'] = input('param.id', 0);
        $show_id = input('param.show_id', 0);
        $where['catalogue_id'] = $param['id'];
        $preMain = Db::name('prepare_lesson')->where($where)->find();
        $catalogue_second = Db::name('teaching_catalogue')->where('id', $preMain['catalogue_id'])->find();
        $catalogue_first = Db::name('teaching_catalogue')->where('id', $catalogue_second['pid'])->find();
        $preMain['second_level_name'] = $catalogue_second['name'];
        $preMain['first_level_name'] = $catalogue_first['name'];
        if ($preMain['cover'] == '') {
            $preMain['cover'] = 'http://image.zgxyzx.net/default.png';
        }
        $admin_upload = Db::name('admin_upload')->where('source_type', '1')->order('file_type', 'ASC')->select();
        for ($i = 0; $i < count($admin_upload); $i++) {
            $courseware['prepare_id'] = $preMain['id'];
            $courseware['courseware_id'] = $admin_upload[$i]['id'];
            $admin_upload[$i]['file_type'] = intval($admin_upload[$i]['file_type']);
            $result = Db::name('prepare_courseware')->where($courseware)->find();
            if (empty($result)) {
                $admin_upload[$i]['hidden'] = true;
            } else {
                $admin_upload[$i]['hidden'] = false;
            }
        }
        $preMain['file'] = $admin_upload;
        if ($show_id > 0) {
            $preMain['description'] = str_replace(array("\r\n", "\r", "\n"), "<br>", $preMain['description']);
        }
        $this->response(1, '获取成功', $preMain);
    }

    public function prepareDetail()
    {
        $param = input('param.', 0);
        $where['catalogue_id'] = $param['id'];
        $tmp['prepare_id'] = Db::name('prepare_lesson')->where($where)->value('id');
        $data = Db::name('prepare_courseware')->where($tmp)->column('courseware_id');
        $search['id'] = ['in', $data];
        $list = Db::name('admin_upload')->where($search)->select();
        for ($i = 0; $i < count($list); $i++) {
            if ($list[$i]['ext'] == 'doc' || $list[$i]['ext'] == 'docx') {
                $list[$i]['thumb'] = 'http://image.zgxyzx.net/word.png';
            } else {
                if ($list[$i]['ext'] == 'ppt' || $list[$i]['ext'] == 'pptx') {
                    $list[$i]['thumb'] = 'http://image.zgxyzx.net/ppt.png';
                } else {
                    if ($list[$i]['ext'] == 'xlsx' || $list[$i]['ext'] == 'xls') {
                        $list[$i]['thumb'] = 'http://image.zgxyzx.net/exl.png';
                    } else {
                        if ($list[$i]['ext'] == 'pdf') {
                            $list[$i]['thumb'] = 'http://image.zgxyzx.net/pdf.png';
                            $list[$i]['covert_url'] = $list[$i]['url'];
                        }
                    }
                }
            }
            if ($list[$i]['file_type'] == 3) {
                $list[$i]['video_type'] = 'kejian';
            }
        }
        $this->response(1, '获取成功', $list);
    }

    public function getList()
    {

        $catalogue_id = input('catalogue_id', 0);
        $content_type = input('content_type', 0);
        $type = input('type', 0);
        $newDataList = array();
        //文件类型，1:图片,2:文档,3:视频
        //内容形式ID，1:视频,2:量表,3:作业,4:课件,5:资讯
        $changeType = array(1 => 3, 2 => 4, 3 => 6, 4 => 2, 5 => 5);
        if (empty($catalogue_id)) {
            $this->response(-1, '目录ID不能为空');
        }
        $teachingModel = new TeachingContent();
        $dataList = $teachingModel->getList($catalogue_id, $content_type, 3);
        if (empty($dataList)) {
            $dataList = array();
        }
        foreach ($dataList as &$value) {
            $value['file_type'] = !empty($changeType[$value['content_type']]) ? $changeType[$value['content_type']] : 0;
        }
        if (!empty($type)) {
            foreach ($dataList as $key => $value2) {
                if ($value2['file_type'] == 3) {
                    $value2['video_type'] = 'neirong';
                }
                if (!empty($value2['content_type'])) {
                    $newDataList[] = $value2;
                }
            }
            $dataList = $newDataList;
        }

        $this->response(1, '成功', $dataList);
    }

    public function getHomeworkInfo()
    {
        $id = input('id', 0, 'int');
        if (empty($id)) {
            $this->response(-1, 'ID不能为空');
        }
        $homeworkModel = new Homework();
        $dataInfo = $homeworkModel->getInfoResourceById($id);
        if ($dataInfo) {
            $this->response(1, '成功', $dataInfo);
        } else {
            $this->response(-1, '查询失败');
        }
    }

    /**
     * @api {post} api/lessonplan.TeacherUse/getSchoolTeachingCombo 教案二级菜单 是否购买
     * @apiVersion 1.0.0
     * @apiName getSchoolTeachingCombo
     * @apiGroup api
     * @apiDescription 教案所有二级菜单 是否购买
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} school_id 学校id .
     * @apiParam {String} pid 教案父级ID .
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getSchoolTeachingCombo()
    {
        $school_id = input('school_id', 0);
        $pid = input('pid', 0);
        $title = input('title', 0);
        if (empty($school_id)) {
            $this->response(-1, '查询失败');
        }
        $model = new Menu();
        $list = $model->getTeachingCombo($school_id, $pid, $title);
        $this->response(1, '查询', $list);
    }

    /**
     * @api {post} api/lessonplan.TeacherUse/getFirstLesson 教案所有一级菜单
     * @apiVersion 1.0.0
     * @apiName getFirstLesson
     * @apiGroup api
     * @apiDescription 教案所有一级菜单 from 张湧
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     * @apiParam {String} school_id 学校id.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getFirstLesson()
    {
        $school_id = input('school_id', 0);
        if (empty($school_id)) {
            $this->response(-1, '查询失败');
        }
        $model = new Menu();
        $list = $model->getTeachingFirstLesson();
        $this->response(1, '查询', $list);
    }

    /**
     * @api {post} api/lessonplan.TeacherUse/getLessonMain 已选择在线课程资源
     * @apiVersion 1.0.0
     * @apiName getLessonMain
     * @apiGroup api
     * @apiDescription 已选择在线课程资源 from 张湧
     *
     * @apiParam {String} school_id 学校id.
     * @apiParam {String} id 教案二级菜单id.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getLessonMain()
    {
        $school_id = input('school_id', 0);
        $id = input('id', 0);
        if (empty($school_id) || empty($id)) {
            $this->response(-1, '参数错误');
        }
        $where['school_id'] = $school_id;
        $combo_id = Db::name('teaching_sale')->where($where)->value('combo_id');
        $chapter_arr = Db::name('teaching_combo')->where('id', $combo_id)->value('chapter_arr');
        $chapter_arr = explode(',', trim($chapter_arr, ','));
        if (!(in_array($id, $chapter_arr))) {
            $this->response(-1, '学校未购买套餐');
        }
        $title = Db::name('teaching_catalogue')->where('id', $id)->value('name');
        $model = new Menu();
        $list = $model->getLessonMain($id);
        if (!empty($list)) {
            for ($j = 0; $j < count($list); $j++) {
                $list[$j]['content_name'] = $this->content_type[$list[$j]['content_type']];
            }
        }
        $this->response(1, '查询', $list, ['title' => $title]);
    }

    public function getLessonAllMain()
    {
        $school_id = input('school_id', 0);
        $id = input('id', 0);
        if (empty($school_id) || empty($id)) {
            $this->response(-1, '参数错误');
        }
        $where['school_id'] = $school_id;
        $combo_id = Db::name('teaching_sale')->where($where)->value('combo_id');
        $chapter_arr = Db::name('teaching_combo')->where('id', $combo_id)->value('chapter_arr');
        $chapter_arr = explode(',', trim($chapter_arr, ','));
        if (!(in_array($id, $chapter_arr))) {
            $this->response(-1, '学校未购买套餐');
        }
        $model = new Menu();
        $list = $model->getLessonMain($id);
        $teachingModel = new TeachingContent();
        $type = 3;
        $teachContent = $teachingModel->getContentDetail($list, $type);
        $title = Db::name('teaching_catalogue')->where('id', $id)->value('name');
        $cover = Db::name('prepare_lesson')->where('catalogue_id', $id)->value('cover');
        $this->response(1, '查询', $teachContent, ['title' => $title, 'cover' => $cover]);
    }
}