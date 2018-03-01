<?php
namespace app\api\controller\college;
use think\Controller;
use think\Loader;
use think\Db;
use app\common\controller\Base;
class Xuanke extends Base
{
     public function CollegeMajor()
     {
        return $this->fetch();
     }
 /**
     * @api {post} /Api/aio.AioTopic/getCollegeMajor 通过省份、选科、查询选科情况
     * @apiVersion              1.0.0
     * @apiName                 getCollegeMajor
     * @apiGROUP                APi
     * @apiDescription          推荐回顾
     * @apiParam {String}       token 已登录账号的token
     * @apiParam {String}       subject     选科科目,   多值用,分割；
     * @apiParam {int}          province_region      所在省份对应region表的ID
     * @apiParam {int}          college_region      所在省份对应region表的ID
     * @apiParam {int}          showay         显示方式：1：按院校；2：按专业
     * @apiParam {int}          num 分页数
     * @apiParam {int}          page 当前页
     *
     * @apiSuccess {Int}        code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String}     msg 成功的信息和失败的具体信息.
     * @apiSuccessExample  {json} Success-Response:
     * {
     * "code": 1,
     * "msg": "获取成功",
     * "data": [
     * {
     *  'region_name' => string '浙江省' (length=9)
     * 'collegecode' => string '10001' (length=5)
     * 'title' => string '北京大学' (length=12)
     * 'schools_type' => string '综合类' (length=9)
     * 'level' => string '本科' (length=6)
     * 'majorname' => string '人文科学试验班(艺术史论\戏剧影视文学\文化产业管理)' (length=73)
     * 'scope' => string '不限' (length=6)
     * }
     * ]
     * }
     *
     */
 public function getCollegeMajor()
    {
        $subject = input('subject/a');
        $region_id = trim(input('province_region'));
        $college_region_id = trim(input('college_region'));
        $showay =  trim(input('showay'));
        $page = input('page', '0', 'int');
        if ($college_region_id <> '0')
            $college_region = db('dd_region')->where('region_id',$college_region_id)->find();
        $college_region = str_replace('省', '', $college_region['region_name']);
        if ($region_id <> '0')
            $real_region = db('dd_region')->where('region_id',$region_id)->find();
        $real_region = str_replace('省', '', $real_region['region_name']);
        $num = input('num', '10', 'int');
        //$page = ($page - 1) * config('paginate')['list_rows']. ',' .config('paginate')['list_rows'];
        $page = ($page - 1) * $num. ',' .$num;
        $token =  trim(input('token'));
        $returnarr = '';
        if ($subject == '' || $region_id == '' || $showay == '') {//空操作
            $this->response('-1','传入参数数据为空');
        }
        elseif ($subject[0] == '0') {
            # 选考科目：不限
            if ($showay == 1) {
                # 按院校
                if ($college_region <> '0' && $college_region <> '') {
                    # 选考科目：不限 # 按院校 # 限区域
                    $returnarr = db('dd_xuanke')
                    ->join('dd_college','dd_college.college_id=dd_xuanke.college_id')
                    ->join('dd_region','dd_region.region_id=dd_xuanke.region_id')
                    ->join('dd_xuanke_scorp','dd_xuanke.xk_id = dd_xuanke_scorp.xk_id','left')
                    ->where('dd_xuanke_scorp.xk_id is null')
                    ->where('dd_college.province',$college_region) 
                    ->field('dd_college.province,dd_college.collegecode,dd_college.title,dd_college.schools_type,dd_xuanke.level,dd_xuanke.majorname,\'不限\' scope') 
                    ->limit($page)
                    ->select();
                }
                else {
                    # 选考科目：不限 # 按院校 # 不限区域
                    $returnarr = db('dd_xuanke')
                    ->join('dd_college','dd_college.college_id=dd_xuanke.college_id')
                    ->join('dd_region','dd_region.region_id=dd_xuanke.region_id')
                    ->join('dd_xuanke_scorp','dd_xuanke.xk_id = dd_xuanke_scorp.xk_id','left')
                    ->where('dd_xuanke_scorp.xk_id is null')
                    ->field('dd_college.collegecode,dd_college.province,dd_college.title,dd_college.schools_type,dd_xuanke.level,dd_xuanke.majorname,\'不限\' scope') 
                    ->order('case when `dd_xuanke`.`level`="本一" then 1 when `dd_xuanke`.`level`="本科" then 2 when `dd_xuanke`.`level`="本科独立院校" then 3 when `dd_xuanke`.`level`="专科" then 8 end desc, case when dd_college.province = \''. $real_region .'\' then 1 end desc')
                    ->limit($page)
                    ->select();
                }
            }
            else if ($showay == 2) {
                # 按专业
                if ($college_region <> '0' && $college_region <> '') {
                    # 选考科目：不限 # 按专业 # 限区域
                    $returnarr = db('dd_xuanke')
                    ->join('dd_college','dd_college.college_id=dd_xuanke.college_id')
                    ->join('dd_region','dd_region.region_id=dd_xuanke.region_id')
                    ->join('dd_xuanke_scorp','dd_xuanke.xk_id = dd_xuanke_scorp.xk_id','left')
                    ->where('dd_xuanke_scorp.xk_id is null')
                    ->where('dd_college.province',$college_region) 
                    ->field('dd_xuanke.major_id,dd_xuanke.majorname,dd_college.schools_type,dd_xuanke.level,\'不限\' scope,dd_xuanke.needyear,dd_college.title')
                    ->order('case when `dd_xuanke`.`level`="本一" then 1 when `dd_xuanke`.`level`="本科" then 2 when `dd_xuanke`.`level`="本科独立院校" then 3 when `dd_xuanke`.`level`="专科" then 8 end desc,dd_xuanke.major_id desc')
                    ->limit($page)
                    ->select();
                }
                else {
                    # 选考科目：不限 # 按专业 # 不限区域
                    $returnarr = db('dd_xuanke')
                    ->join('dd_college','dd_college.college_id=dd_xuanke.college_id')
                    ->join('dd_region','dd_region.region_id=dd_xuanke.region_id')
                    ->join('dd_xuanke_scorp','dd_xuanke.xk_id = dd_xuanke_scorp.xk_id','left')
                    ->where('dd_xuanke_scorp.xk_id is null')
                    ->field('dd_xuanke.major_id,dd_xuanke.majorname,dd_college.schools_type,dd_xuanke.level,\'不限\' scope,dd_xuanke.needyear,dd_college.title')
                    ->order('case when `dd_xuanke`.`level`="本一" then 1 when `dd_xuanke`.`level`="本科" then 2 when `dd_xuanke`.`level`="本科独立院校" then 3 when `dd_xuanke`.`level`="专科" then 8 end desc, case when dd_college.province = \''. $real_region .'\' then 1 end desc,dd_xuanke.major_id desc')
                    ->limit($page)
                    ->select();
                }
            }
        }
        else {
            # 选考科目：有
            $str_subject =  implode(',' , $subject);
            if ($showay == 1) {
                # 按院校
                if ($college_region <> '0' && $college_region <> '') {
                    # 选考科目：有 # 按院校 # 限区域
                    $returnarr = 
                    db('view_xuanke')
                    ->where ('xk_gp_id',$str_subject)
                    ->where('province',$college_region) 
                    ->limit($page)
                    ->field('province,collegecode,title,schools_type,school_level,majorname,xk_record scope') 
                    ->order('case when `school_level`="本一" then 1 when `school_level`="本科" then 2 when `school_level`="本科独立院校" then 3 when `school_level`="专科" then 8 end desc, case when province = \''. $real_region .'\' then 1 end desc')
                    ->select();
                }
                else {
                    # 选考科目：有 # 按院校 # 不限区域
                $returnarr = 
                    db('view_xuanke')
                    ->where ('xk_gp_id',$str_subject)
                    ->limit($page)
                    //->field("major_id","needyear","province","collegeCode","title","schools_type","school_level","majorName","xk_record")
                    ->field('province,collegecode,title,schools_type,school_level,majorname,xk_record scope') 
                    ->order(' case when province = \''. $real_region .'\' then 1 end desc,case when `school_level`="本一" then 1 when `school_level`="本科" then 2 when `school_level`="本科独立院校" then 3 when `school_level`="专科" then 8 end,title desc')
                    ->select();
                }
            }
            else if ($showay == 2) {
                # 按专业
              if ($college_region <> '0' && $college_region <> '') {
                    # 选考科目：有 # 按专业 # 限区域
                    $returnarr = 
                    db('view_xuanke')
                    ->where ('xk_gp_id',$str_subject)
                    ->where('province',$college_region) 
                    ->limit($page)
                    ->field('major_id,majorname,schools_type,school_level,xk_record scope,needyear,title')
                    ->order('case when `school_level`="本一" then 1 when `school_level`="本科" then 2 when `school_level`="本科独立院校" then 3 when `school_level`="专科" then 8 end desc, case when province = \''. $real_region .'\' then 1 end desc,majorname desc')
                    ->select();
                }
                else {
                    # 选考科目：有 # 按专业 # 不限区域
                $returnarr = 
                    db('view_xuanke')
                    ->where ('xk_gp_id',$str_subject)
                    ->limit($page)
                    ->field('major_id,majorname,schools_type,school_level,xk_record scope,needyear,title')
                    ->order(' case when province = \''. $real_region .'\' then 1 end desc,case when `school_level`="本一" then 1 when `school_level`="本科" then 2 when `school_level`="本科独立院校" then 3 when `school_level`="专科" then 8 end,majorname desc')
                    ->select();
                }
            }
        }
        $this->response('1','query ok',$returnarr);

    }




}