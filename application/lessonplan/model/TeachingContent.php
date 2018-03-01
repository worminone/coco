<?php

namespace app\lessonplan\model;

use Think\Db;
use think\Model;

class TeachingContent extends Model
{
    protected $pk = 'id';

    /**
     * 查询内容列表
     * @return array()
     */
    public function getList($catalogue_id, $content_type, $type = 1)
    {
        $where['catalogue_id'] = $catalogue_id;
        if (!empty($content_type)) {
            $where['content_type'] = $content_type;
        }
        $content = Db::name('teaching_content')->where($where)->select();
        $teachContent = $this->getContentDetail($content, $type);
        return $teachContent;
    }

    /*
     * 根据内容得到内容扩展信息
     */
    public function getContentDetail($content, $type = 1)
    {
        $teachContent = array();//内容对应目录数组
        $adminUpload = array();//视频
        $kj = array();//课件
        $homework = array();//作业
        $test = array();//测评
        $article = array();//资讯
        if (!empty($content)) {
            foreach ($content as $value) {
                $teachContent[] = $value;
                if (in_array($value['content_type'], array(1))) {
                    $adminUpload[] = $value['upload_id'];
                } else {
                    if (in_array($value['content_type'], array(4))) {
                        $kj[] = $value['upload_id'];
                    } else {
                        if (in_array($value['content_type'], array(3))) {//作业
                            $homework[] = $value['upload_id'];
                        } else {
                            if (in_array($value['content_type'], array(2))) {//量表
                                $test[] = $value['upload_id'];
                            } else {
                                if (in_array($value['content_type'], array(5))) {//资讯
                                    $article[] = $value['upload_id'];
                                }
                            }
                        }
                    }
                }
            }
        }
        //批量查询
        if (!empty($adminUpload)) {//视频
            $adminWhere['id'] = array('in', $adminUpload);
            $adminList = Db::name('admin_upload')->field('id,file_name as title,thumb as cover,url,description,covert_url')->where($adminWhere)->select();
            if (!empty($adminList)) {
                foreach ($adminList as $adminValue) {
                    $adminUpload[$adminValue['id']] = $adminValue;
                }
            }
        }
        //课件
        if (!empty($kj)) {
            $kjWhere['id'] = array('in', $kj);
            $adminList = Db::name('admin_upload')->field('id,file_name as title,thumb as cover,ext,url,description,covert_url')->where($kjWhere)->select();
            if (!empty($adminList)) {
                foreach ($adminList as $adminValue) {
                    if ($adminValue['ext'] == 'pdf') {
                        $adminValue['covert_url'] = $adminValue['url'];
                    }
                    $kj[$adminValue['id']] = $adminValue;
                }
            }
        }
        if (!empty($homework)) {//作业
            $homeWhere['id'] = array('in', $homework);
            $homeList = Db::name('homework')->field('id,description as title,cover,content,resource,answer_type')->where($homeWhere)->select();
            if (!empty($homeList)) {
                foreach ($homeList as $keys=>$homeValue) {
                    $homework[$homeValue['id']] = $homeValue;
                }
            }
        }
        if (!empty($test)) {//量表
            $testWhere['id'] = array('in', $test);
            //请求测评接口
            $testList = $this->getTestTitle(implode(',', $test), $type);
            if (!empty($testList['data'])) {
                foreach ($testList['data'] as $testValue) {
                    $test[$testValue['type_id']] = $testValue;
                }
            }
        }
        if (!empty($article)) {//资讯
            //请求资讯接口
            $articleList = $this->getArticleInfo(implode(',', $article));
            if (!empty($articleList['datas'])) {
                foreach ($articleList['datas'] as $articleValue) {
                    $article[$articleValue['id']] = $articleValue;
                }
            }
        }
        if (!empty($teachContent)) {
            foreach ($teachContent as &$teachValue) {
                if (in_array($teachValue['content_type'],
                        array(1)) && !empty($adminUpload[$teachValue['upload_id']])
                ) {//视频
                    $teachValue['title'] = $adminUpload[$teachValue['upload_id']]['title'];
                    $teachValue['cover'] = $adminUpload[$teachValue['upload_id']]['cover'];
                    $teachValue['url'] = $adminUpload[$teachValue['upload_id']]['url'];
                    $teachValue['description'] = $adminUpload[$teachValue['upload_id']]['description'];
                    $teachValue['ext'] = '';
                    $teachValue['covert_url'] = $adminUpload[$teachValue['upload_id']]['covert_url'];
                } else {
                    if (in_array($teachValue['content_type'],
                            array(4)) && !empty($kj[$teachValue['upload_id']])
                    ) {//课件
                        $teachValue['title'] = $kj[$teachValue['upload_id']]['title'];
                        $teachValue['cover'] = getCoverByExt($kj[$teachValue['upload_id']]['ext']);
                        $teachValue['url'] = $kj[$teachValue['upload_id']]['url'];
                        $teachValue['description'] = $kj[$teachValue['upload_id']]['description'];
                        $teachValue['ext'] = $kj[$teachValue['upload_id']]['ext'];
                        $teachValue['covert_url'] = $kj[$teachValue['upload_id']]['covert_url'];
                    } else {
                        if (in_array($teachValue['content_type'],
                                array(3)) && !empty($homework[$teachValue['upload_id']])
                        ) {//作业
                           // var_dump($homework);
                            $teachValue['title'] = $homework[$teachValue['upload_id']]['title'];
                            $teachValue['cover'] = $homework[$teachValue['upload_id']]['cover'];
                            $teachValue['description'] = $homework[$teachValue['upload_id']]['content'];
                            if($homework[$teachValue['upload_id']]['answer_type']==1){
                                $teachValue['answer_type_name'] = '文字';
                            }elseif($homework[$teachValue['upload_id']]['answer_type']==2){
                                $teachValue['answer_type_name'] = '图片';
                            }else{
                                $teachValue['answer_type_name'] = '图文';
                            }
                            $teachValue['ext'] = '';
                            if (!empty($homework[$teachValue['upload_id']]['resource'])) {
                                $resourceArr = explode(',', $homework[$teachValue['upload_id']]['resource']);
                                $resourceArr = array_filter($resourceArr);
                                foreach ($resourceArr as $adminId) {
                                    $uploadModel = new AdminUpload();
                                    $upload = $uploadModel->getInfoById($adminId);
                                    if (!empty($upload)) {
                                        if($upload['file_type']==1){
                                            $icon = '/static/img/ico28.png';
                                            $icon_name = '图片';
                                        }elseif($upload['file_type']==2){
                                            $icon = '/static/img/ico25.png';
                                            $icon_name = '文档';
                                        }else{
                                            $icon = '/static/img/ico22.png';
                                            $icon_name = '视频';
                                        }

                                        if(strtolower($upload['ext'])=='pdf'||$upload['file_type']==1||$upload['file_type']==3){
                                            $upload['covert_url'] =  $upload['url'];
                                        }
                                        $teachValue['resourceList'][] = array(
                                            'id' => $upload['id'],
                                            'url' => $upload['url'],
                                            'icon' => $icon,
                                            'icon_name' => $icon_name,
                                            'covert_url' => $upload['covert_url'],
                                            'file_name' => $upload['file_name'],
                                            'file_type' => $upload['file_type'],
                                            'thumb' => ($upload['file_type'] == 2) ? getCoverByExt($upload['ext']) : $upload['thumb'],
                                            'description' => !empty($upload['description']) ? $upload['description'] : '',
                                            'ext' => $upload['ext']
                                        );
                                    }
                                }
                            }
                        } else {
                            if (in_array($teachValue['content_type'],
                                    array(2)) && !empty($test[$teachValue['upload_id']])
                            ) {//量表
                                $teachValue['title'] = $test[$teachValue['upload_id']]['type_name'];
                                $teachValue['description'] = $test[$teachValue['upload_id']]['desc'];
                                if (!empty($test[$teachValue['upload_id']]['cover'])) {
                                    $teachValue['cover'] = $test[$teachValue['upload_id']]['cover'];
                                } else {
                                    $teachValue['cover'] = '';
                                }
                                if (!empty($test[$teachValue['upload_id']]['descPic'])) {
                                    $teachValue['descPic'] = $test[$teachValue['upload_id']]['descPic'];
                                } else {
                                    $teachValue['descPic'] = '';
                                }
                                $teachValue['ext'] = '';
                            } else {
                                if (in_array($teachValue['content_type'],
                                        array(5)) && !empty($article[$teachValue['upload_id']])
                                ) {//资讯
                                    $teachValue['title'] = $article[$teachValue['upload_id']]['title'];
                                    $teachValue['cover'] = !empty($article[$teachValue['upload_id']]['pic_url']) ? getGZPic($article[$teachValue['upload_id']]['pic_url']) : getDefaultPic();
                                    $teachValue['url'] = '';
                                    $teachValue['description'] = $article[$teachValue['upload_id']]['content'];
                                    $teachValue['ext'] = '';
                                } else {
                                    if ($teachValue['content_type'] == 0) {
                                        $teachValue['title'] = '请选择内容';
                                        $teachValue['url'] = '';
                                        $teachValue['description'] = '';
                                        $teachValue['cover'] = '';
                                        $teachValue['ext'] = '';
                                    }
                                }
                            }
                        }
                    }
                }
                $contentUrl = config('student_app_api') . '/api/Classroom/getInfo.php?id=' . $teachValue['id'];
                $teachValue['QRcode'] = config('base_api') . '/api/qrcode/getqr?url=' . $contentUrl;
            }
        }
        return $teachContent;
    }

    /**
     * 查询内容列表二维码用
     * @return array()
     */
    public function getListQR()
    {
        $menuModel = new Menu();
        $menuArr = $menuModel->getMenuList();//全部目录数组
        if (empty($menuArr)) {
            return array();
        }
        foreach ($menuArr as $menu) {
            if (!empty($menu)) {
                $catalogueArr[] = $menu['id'];
            }
            if (!empty($menu['children'])) {
                foreach ($menu['children'] as $children) {
                    $catalogueArr[] = $children['id'];
                }
            }
        }
        $where['catalogue_id'] = array('in', $catalogueArr);
        $content = Db::name('teaching_content')->where($where)->select();
        $teachContent = $this->getContentDetail($content);
        if (!empty($menuArr)) {
            foreach ($menuArr as &$menuFirst) {
                if (!empty($teachContent[$menuFirst['id']])) {
                    $menuFirst['teaching'][] = $teachContent[$menuFirst['id']];
                }
                if (!empty($menuFirst['children'])) {
                    foreach ($menuFirst['children'] as &$second) {
                        if (!empty($teachContent)) {
                            foreach ($teachContent as $key => $teachValue) {
                                if ($teachValue['catalogue_id'] == $second['id']) {
                                    $second['teaching'][] = $teachValue;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $menuArr;
    }

    /*
* 根据id批量查询测评信息
* @param $id 多个id用“,”分隔
* @param $type (0 旧App,1新App,2一体机,3.新高中三端）
* @return String
*/
    public function getTestTitle($id, $type = 1)
    {
        $url = config('evaluate_api') . '/api/question/getInfoById';
        $data['id'] = $id;
        $data['type'] = $type;
        $output = tocurlArray($url, $data);
        return $output;
    }

    /**
     * 根据id批量查询资讯信息
     * @param $id 多个id用“,”分隔
     * @return String
     */
    public function getArticleInfo($id)
    {
        $url = config('gz_api') . '/index.php?app=school&act=art_content&type_id=4&id=' . $id;

        $output = file_get_contents($url);
        if (preg_match('/^\xEF\xBB\xBF/', $output))    //去除可能存在的BOM
        {
            $output = substr($output, 3);
        }
        $output = json_decode($output, true);
        return $output;
    }

    /**
     * 根据type_id查询测评信息
     * @param $type_id
     * @return String
     */
    public function getTestDetail($type_id)
    {
        $url = config('evaluate_api') . '/api/question/getInfo';
        $data['type_id'] = $type_id;
        $output = tocurlArray($url, $data);
        return $output;
    }


    /**
     * 获取测评题目列表
     * @return String
     */
    public function getTestList($type = 1)
    {
        $url = config('evaluate_api') . '/api/Question/getList';
        $data = array('type' => $type);
        $output = tocurlArray($url, $data);
        return $output;
    }

    /**
     * 获取资讯列表
     * @return String
     */
    public function getArticleList()
    {
        $url = config('gz_api') . '/index.php?app=school&act=art_list&type_id=4&equipment_id=5';
        $output = file_get_contents($url);
        if (preg_match('/^\xEF\xBB\xBF/', $output))    //去除可能存在的BOM
        {
            $output = substr($output, 3);
        }
        $output = json_decode($output, true);
        return $output;
    }

    /**
     * 批量删除内容
     * @param $id 内容ID 批量用“,”分隔
     * @return int
     */
    public function delInfo($id)
    {
        if (empty($id)) {
            return 0;
        }
        //权限判断
        $idArr = explode(',', $id);
        $where['content_type'] = ['in',[1,4]];
        $where['upload_id'] = ['in',$idArr];
        $count  = Db::name('teaching_content')->where($where)->count();
        if($count>0){
            return 0;
        }
        $flag = Db::name('teaching_content')->delete($idArr);
        return $flag;
    }

    /**
     * 批量删除某种类型的教案内容
     * @param $content_type 内容形式ID，1:视频,2:量表,3:作业,4:课件,5:资讯'
     * @param $upload_id 资源ID,批量为","隔开
     */
    public function delTypeContent($content_type, $upload_id)
    {
        if (!empty($upload_id)) {
            $upload_Arr = explode(',', $upload_id);
            $where['upload_id'] = array('in', $upload_Arr);
        } else {
            return false;
        }
        $where['content_type'] = $content_type;
        $flag = Db::name('teaching_content')->where($where)->delete();
        return $flag;
    }

    /**
     * 添加内容
     * @param $data
     * @return int
     */
    public function addInfo($data)
    {
        if (empty($data)) {
            return 0;
        }
        $flag = Db::name('teaching_content')->insertGetId($data);
        return $flag;
    }

    /**
     * 根据ID查询内容数据
     * @param $id
     * @param $school_id
     * @return array|false|int|mixed|\PDOStatement|string|Model
     */
    public function getInfo($id, $school_id)
    {
        if (empty($id)) {
            return 0;
        }
        $flag = Db::name('teaching_content')->where(['id' => $id])->find();
        if (!empty($school_id)) {
            $isInfo = $this->isSchoolContent($flag['catalogue_id'], $school_id);
            if (!$isInfo) {
                return -1;
            }
        }
        if (!empty($flag) && in_array($flag['content_type'], array(1, 4))) {
            $adminInfo = Db::name('admin_upload')->where(['id' => $flag['upload_id']])->find();
            if (!empty($adminInfo)) {
                $flag = array_merge($flag, $adminInfo);
            }
        }
        return $flag;
    }

    /**
     * 根据ID查询内容数据
     * @param $id
     * @return array|false|int|mixed|\PDOStatement|string|Model
     */
    public function getUploadInfo($id)
    {
        if (empty($id)) {
            return 0;
        }
        $adminInfo = Db::name('admin_upload')->where(['id' => $id])->find();
        return $adminInfo;
    }

    /**
     * 根据内容及学校ID判断是否有权限
     * @param $catalogue_id
     * @param $school_id
     */
    public function isSchoolContent($catalogue_id, $school_id)
    {
        $flag = false;
        $teacherSale = Db::name('teaching_sale')->where(['school_id' => $school_id])->select();
        if (!empty($teacherSale)) {
            foreach ($teacherSale as $teachValue) {
                if (!empty($teachValue['combo_id'])) {
                    $where['chapter_arr'] = array('like', '%,' . $catalogue_id . ',%');
                    $where['id'] = $teachValue['combo_id'];
                    $comboInfo = Db::name('teaching_combo')->where($where)->find();
                    if (!empty($comboInfo)) {
                        $flag = true;
                        break;
                    }
                }
            }
        }
        return $flag;

    }

    /**
     * 编辑内容
     * @param $id
     * @param $data
     * @return int
     */
    public function editInfo($id, $data)
    {
        if (empty($id) || empty($data)) {
            return 0;
        }
        $flag = Db::name('teaching_content')->where('id', $id)->update($data);
        return $flag;
    }

    /**
     *批量导出二维码
     */
    function exportWord()
    {
        ini_set('max_execution_time', '0');
        vendor("phpword.PHPWord");
        $PHPWord = new \PHPWord();
        $section = $PHPWord->createSection();
        $menuList = $this->getListQR();
        if (!empty($menuList)) {
            foreach ($menuList as $firstMenu) {
                $firstMenu['label'] = $firstMenu['label'];//iconv("gb2312","utf-8//IGNORE",$firstMenu['name']);
                $section->addText($firstMenu['label']);
                if (!empty($firstMenu['children'])) {
                    foreach ($firstMenu['children'] as $secMenu) {
                        $section->addText('--------' . $secMenu['label']);
                        if (!empty($secMenu['teaching'])) {
                            foreach ($secMenu['teaching'] as $content) {
                                if (!empty($content['title'])) {
                                    $section->addText('----------------' . $content['title']);
                                    $section->addMemoryImage($content['QRcode']);
                                }
                            }
                        }
                    }
                }
            }
        }
        $fileName = "批量导出二维码" . date("YmdHis");
        exportWord($fileName, $PHPWord);
    }
}