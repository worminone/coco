<?php

namespace app\lessonplan\model;

use Think\Db;
use think\Model;

class Homework extends Model
{
    protected $pk = 'id';
    private $answer_type = array(1 => '文字', 2 => '图片', 3 => '图文');//回答方式

    /**
     * 查询作业列表
     * @param $where
     * @param string $order
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($where, $order = 'id DESC', $limit = "0,10")
    {
        $data = array();
        $where['status'] = 0;
        $data['total'] = Db::name('homework')->where($where)->count();
        $data['list'] = Db::name('homework')->field('id,description,cover')->where($where)->order($order)->limit($limit)->select();
        return $data;
    }

    /**
     * 批量删除作业
     * @param $id 作业ID 批量用“,”分隔
     * @return int
     */
    public function delInfo($id)
    {
        if (empty($id)) {
            return 0;
        }
        $content['upload_id'] = ['in', $id];
        //文档删除教案内容重置
        $content['content_type'] = 3;
        $count = Db::name('teaching_content')->where($content)->count();
        if($count>0){
            return 0;
        }
        //权限判断
        $idArr = explode(',', $id);
        $where['id'] = array('in', $idArr);
        $flag = Db::name('homework')->where($where)->update(['status' => 1]);
        //删除目录内容中引用的作业
        $teachModel = new TeachingContent();
        $teachModel->delTypeContent(3, $id);
        return $flag;
    }

    /**
     * 添加作业
     * @param $data
     * @return int
     */
    public function addInfo($data)
    {
        if (empty($data)) {
            return 0;
        }
        $flag = Db::name('homework')->insertGetId($data);
        return $flag;
    }

    /**
     * 编辑作业
     * @param $id
     * @param $data
     * @return int
     */
    public function editInfo($id, $data)
    {
        if (empty($id) || empty($data)) {
            return 0;
        }
        $flag = Db::name('homework')->where('id', $id)->update($data);
        return $flag;
    }

    /**
     * 根据作业ID查询作业详情
     * @param $id
     */
    public function getInfoById($id)
    {
        $data = Db::name('homework')->field('id,description,content,answer_type,resource,cover')->where(['id' => $id])->find();
        return $data;
    }

    /**
     * 根据作业ID查询作业详情(包含附件信息)
     * @param $id
     */
    public function getInfoResourceById($id)
    {
        $data = Db::name('homework')->field('id,description,content,answer_type,resource,cover')->where(['id' => $id])->find();
        if ($data) {
            $data['answer_type_name'] = $this->answer_type[$data['answer_type']];
            if (!empty($data['resource'])) {
                $resourceArr = explode(',', $data['resource']);
                $resourceArr = array_filter($resourceArr);
                foreach ($resourceArr as $adminId) {
                    $uploadModel = new AdminUpload();
                    $upload = $uploadModel->getInfoById($adminId);
                    if (!empty($upload)) {
                        $data['resourceList'][] = array(
                            'id' => $upload['id'],
                            'url' => $upload['url'],
                            'file_name' => $upload['file_name'],
                            'file_type' => $upload['file_type'],
                            'thumb' => ($upload['file_type'] == 2) ? getCoverByExt($upload['ext']) : $upload['thumb'],
                            'description' => !empty($upload['description']) ? $upload['description'] : '',
                            'ext' => $upload['ext'],
                            'covert_url' => $upload['covert_url']
                        );
                    }
                }
            }
            $data['resourceList'] = !empty($data['resourceList']) ? $data['resourceList'] : array();
        }
        return $data;
    }

    /*根据作业ID查询资源列表,ID为空则返回全部
     *@param $id
     */
    public function getResource($id)
    {
        $dataList = array();
        $selectArr = array();
        $homeworkInfo = $this->getInfoById($id);
        if (!empty($homeworkInfo['resource'])) {
            $selectArr = explode(',', $homeworkInfo['resource']);
            $selectArr = array_filter($selectArr);
        }
        $uploadModel = new AdminUpload();
        $where['file_type']  = ['in', '1,2,3'];
        $data = $uploadModel->getList($where, 'id DESC', '0,9999');
        if (!empty($data['list'])) {
            foreach ($data['list'] as $value) {
                $dataList[] = array_merge($value,
                    array('hidden' => in_array($value['id'], $selectArr) ? false : true));
            }
        }
        $dataList = arraySequence($dataList, 'file_type', 'SORT_ASC');
        return $dataList;
    }

}
