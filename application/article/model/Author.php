<?php

namespace app\article\model;

use think\Db;

class Author
{
    //ID 获取 咨询信息
    public function getAuthorList($where)
    {
        return DB::name('UserAuthor')
            ->where($where)
            ->select();
    }

    public function getAuthorName($user_id, $name)
    {
        return DB::name('UserAuthor')
            ->where('user_id', 'not in', $user_id)
            ->where(['name'=>$name])
            ->find();
    }

    //查询用户信息
    public function getAuthorInfo($id)
    {
        $a_info = DB::name('UserAuthor')->where(['id'=>$id])->find();
        if($a_info) {
            $u_info = DB::name('AdminUser')->where(['id'=>$a_info['user_id']])->find();
            $data['username'] =  $a_info['name'];
            $data['avatar'] =  $u_info['avatar'];
            $data['id'] =  $id;
       }else{
            $data['username'] =  '匿名';
            $data['avatar'] =  '';
            $data['id'] =  $id;
       }
       return $data;
        
    }

}
