<?php

namespace app\message\model;

use think\Db;

class Message
{

    protected $feedback = [
        '1' => '产品体验',
        '2' => '客服态度',
        '3' => '售后服务',
        '4' => '产品质量',
        '5' => '物流问题',
        '6' => '课件资源',
    ];
    protected $school_feedback = [
        '1' => '故障报修',
        '2' => '问题反馈',
        '3' => '需求建议',
    ];
    protected $term_type = [
        '1' => '一体机',
        '2' => 'app',
        '3' => '校园在线官网',
    ];

    // 获取 官网问题反馈类型
    public function getFeedback()
    {
        $info = $this->feedback;
        foreach ($info as $key => $value) {
            $infos[$key]['id'] = (string)$key;
            $infos[$key]['name'] = $value;
        }
        $infos = array_values($infos);
        return $infos;
    }
    // 获取 问题反馈类型
    public function getSchoolFeedback()
    {
        $info = $this->school_feedback;
        foreach ($info as $key => $value) {
            $infos[$key]['id'] = (string)$key;
            $infos[$key]['name'] = $value;
        }
        $infos = array_values($infos);
        return $infos;
    }

    // 获取 教务端问题反馈类型
    public function getQuestion()
    {
        $info = $this->school_feedback;
        foreach ($info as $key => $value) {
            $infos[$key]['id'] = (string)$key;
            $infos[$key]['name'] = $value;
        }
        $infos = array_values($infos);
        return $infos;
    }

    //终端类型
    public function getTermType()
    {
       return $this->term_type;
    }
    //问题反馈类型
    public function getFeedbackType()
    {
        return $this->feedback;
    }

    // 获取 教务端问题反馈类型
    public function getQuestionType()
    {
        return $this->school_feedback;
    }


    /**
     * 添加信息
     */
    public function addInfo($type = 0, $post_id = 0, $title = "", $content = "")
    {
        $data = [];
        $data['type'] = $type;
        $data['post_id'] = $post_id;
        $data['title'] = $title;
        $data['content'] = $content;
        $id = Db::name('Message')->insertGetId($data);
        return $id;
    }
}
