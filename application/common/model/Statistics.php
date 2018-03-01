<?php
namespace app\common\model;

use think\Model;

class Statistics extends Model
{

    //广告点击量统计
    public function adClickStatistics($data)
    {
        $where = [];
        $where['ad_id'] = $data['is_ad'];
        $where['province_id'] = $data['province_id'];
        $where['the_year'] = $data['the_year'] = date('y');
        $where['the_month'] = $data['the_month'] = date('m');
        $where['the_date'] = $data['the_date'] = date('Y-m-d');


        $count = db('ad_statistics')->where($where)->count();
        $bool = false;
        if ($count) {
            $bool = db('ad_statistics')->where($where)->setInc('click_count');
        } else {
            $data['click_count'] = 1;
            $bool = db('ad_statistics')->insert($data);
        }

        return $bool;
    }


    //广告展示量统计
    public function adShowStatistics($data)
    {
        $where = [];
        $where['ad_id'] = $data['ad_id'];
        $where['province_id'] = $data['province_id'];
        $where['the_year'] = $data['the_year'] = date('y');
        $where['the_month'] = $data['the_month'] = date('m');
        $where['the_date'] = $data['the_date'] = date('Y-m-d');


        $count = db('ad_statistics')->where($where)->count();
        $bool = false;
        if ($count) {
            $bool = db('ad_statistics')->where($where)->setInc('view_count');
        } else {
            $data['view_count'] = 1;
            $bool = db('ad_statistics')->insert($data);
        }

        return $bool;

    }


    //高中学校统计情况
    public function highSchoolStatistics()
    {
        $data = ['name'=>'高中学校统计情况', 'count'=>106, 'is_added'=> 1];
        return $data;
    }

    //教师用户使用情况
    public function teacherStatistics()
    {
        $data = ['name'=>'教师用户使用情况', 'count'=>1869, 'is_added'=> 1];
        return $data;
    }

    //学生用户使用情况
    public function studentStatistics()
    {
        $data = ['name'=>'学生用户使用情况', 'count'=>11236, 'is_added'=> 0];
        return $data;
    }

    //家长用户使用情况
    public function parentStatistics()
    {
        $data = ['name'=>'家长用户使用情况', 'count'=>11389, 'is_added'=> -1];
        return $data;
    }

    //大学用户使用情况
    public function collegeStatistics()
    {
        $data = ['name'=>'大学用户使用情况', 'count'=>269, 'is_added'=> 1];
        return $data;
    }

    //升学一体机使用情况
    public function highScoolAioStatistics()
    {
        $data = ['name'=>'升学一体机使用情况', 'count'=>370672, 'is_added'=> 1];
        return $data;
    }

    //平台产品文件资料管理情况
    public function productFileStatistics()
    {
        $data = ['name'=>'平台产品文件资料管理情况', 'count'=>1083, 'is_added'=> 0];
        return $data;
    }

    //平台资源文件上传情况
    public function uploadFileStatistics()
    {
        $data = ['name'=>'平台资源文件上传情况', 'count'=>140930, 'is_added'=> -1];
        return $data;
    }


}