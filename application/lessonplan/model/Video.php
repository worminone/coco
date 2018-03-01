<?php

namespace app\lessonplan\model;

use think\Db;

class Video
{
    public function getVideoCount($where)
    {
        $total = Db::name('admin_upload')->where($where)->count();
        return $total;
    }

    public function getVideoData($where, $limit)
    {
        $list = Db::name('admin_upload')->where($where)->order('create_time desc')->limit($limit)->select();
        return $list;
    }

    public function delupload($data)
    {
        $content['upload_id'] = ['in', $data];
        $content2['courseware_id'] = ['in', $data];
        $count2 = Db::name('prepare_courseware')->where($content2)->count();
        if($count2>0){
            return 0;
        }
        //文档删除教案内容重置
        $updateData['content_type'] = 0;
        $updateData['upload_id'] = 0;
        $content['content_type'] = ['in', '1,4'];
        $count = Db::name('teaching_content')->where($content)->count();
        if($count>0){
            return 0;
        }
        $flag['id'] = ['in', $data];
        Db::name('admin_upload')->where($flag)->delete();
        $where['courseware_id'] = ['in', $data];
        Db::name('prepare_courseware')->where($where)->delete();
        $tmp = explode(',', $data);
        foreach ($tmp as $value) {
            $sql = "update dd_homework set resource=replace(resource,'," . $value . "','')";
            Db::execute($sql);
        }
        return 1;
    }

    public function getVideoDetail($id)
    {
        $where['id'] = $id;
        $detail = Db::name('admin_upload')->where($where)->find();
        return $detail;
    }
}
