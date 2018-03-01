<?php
namespace app\article\model;

use think\Model;
use think\Db;


class SlideShow extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';



    //内部接口调用
    public function getList($where, $page, $pageSize)
    {
        $field = '*';
        $count = $this->field($field)->where($where)->count();
        $list = db('slide_show')->field($field)->where($where)->limit($pageSize)->page($page)->order('id desc')->select();
        // $regionData = get_region_name();
        $region = get_region_list();
        foreach ($list as &$one) {
            $one = $this->detail($one, $region);
        }

        return ['count'=>$count,'pagesize'=>config('pagesize'), 'data'=>$list];
    }


    //获取单个轮播图数据
    public function getOne($id)
    {
        $field = '*';
        $data = db('slide_show')->field($field)->find($id);
//         aa($data);
        $region = get_region_list();
        $data = $this->detail($data, $region);

        return $data;
    }

    //轮播图详细数据
    private function detail($data, $region)
    {
        $rs = $data;//category_id
        $data['term_name'] = db('term_type')->where(array('id'=>$rs['term_type']))->value('name');
        $data['jump_obj_title'] = config('slide_show_jump')[$rs['jump_obj']];
        $data['category_name'] = db('article_category')->where(array('id'=>$rs['category_id']))->value('name');

        if ($rs['province'] == 0) {
            $data['province_name'] = '全国';
        } else {
            $provinceName = ',';
            $regionIdArr = explode(',', $data['province']);
            foreach ($regionIdArr as $v) {

                $provinceName .= get_region_name($v) . ',';
            }

            $provinceName = trim($provinceName, ',');
            $data['province_name'] = $provinceName;
        }

        return $data;
    }

    //外部接口调用
    public function apiList($where, $limit)
    {
        $map = [];
        $map['a.status'] = 1;
        $map['a.ad_id'] = 0;
        $map['term_type'] = key_exists('term_type', $where) ? $where['term_type'] : 1;
        if (key_exists('category_id', $where) && $where['category_id']) {
            $map['category_id'] = $where['category_id'];
        }
        $map['b.province'] = ['in', [0,$where['province']]];


        $field = 'a.id as slide_id,title, image_url, jump_obj, obj_value,create_time,ad_id,sort';
        //$field = 'DISTINCT a.id as slide_id,title, image_url, jump_obj, obj_value,create_time,ad_id';
        $list = db('slide_show')->alias('a')
                     ->join('dd_slide_province b','a.id = b.slide_id','LEFT')
                     ->field($field)
                     ->where($map)
                     ->limit($limit)
                     ->order('sort desc,create_time desc')
                     ->select();
//         echo db()->getLastSql();exit;

        foreach ($list as &$one) {
            //如果跳转是文章的话，文章要有原始标题
            if ($one['jump_obj'] == 1 && intval($one['obj_value']) ) {
                $one['origin_title'] = db('article')->where('id='.intval($one['obj_value']))->value('title');
            } else {
                $one['origin_title'] = '';
            }

        }

        return $list;
    }

    //添加一个轮播图,会关联到轮播图的投放区域
    public function addOne($data)
    {

        //编辑
        if (key_exists('id', $data)) {
            $this->allowField(true)->save($data, ['id'=>$data['id']]);
            $id = $data['id'];
            db('slide_province')->where('slide_id=' . $id)->delete();
        } else { //添加
            $id = db('slide_show')->insertGetId($data);
        }

        $provinceArr = explode(',', $data['province']);

        $regionData = [];

        $i = 0;
        foreach ($provinceArr as $one) {
            $regionData[$i] = ['slide_id'=>$id, 'province'=>$one];
            $i++;
        }

        //更新投放区域
        db('slide_province')->insertAll($regionData);

        return $id;

    }

    public function getJumpObj(){
        $obj_values = Db::name('SlideShow')->where(['jump_obj'=>1])->column('obj_value');
        return $obj_values;
    }
}