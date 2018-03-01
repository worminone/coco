<?php
namespace app\ad\model;

use think\Model;
use think\Db;
use app\article\model\SlideShow;
use app\article\model\Article;
use app\article\model\Common;


class Ad extends Model
{
    protected $pk = 'id';
    //protected $autoWriteTimestamp = 'datetime';
    //protected $table = 'dd_ad';


    //时间交集部分的sql，多处用到
    private function timeOverlap($data)
    {
        $a = $data['start_date'];
        $b = $data['end_date'];
        $timeOverLap = " (
                            (start_date >= '$a' AND start_date <= '$b') OR
                            (start_date <= '$a' AND end_date >= '$b') OR
                            (end_date >= '$a' AND end_date <= '$b')
                         ) ";

        return $timeOverLap;
    }

    //内部接口调用
    public function getList($where, $page=1, $pageSize=1)
    {

        $list = db('ad')->alias('a')->distinct(true)->field('a.*')
                ->join('dd_ad_province b','a.id = b.ad_id','LEFT')->where($where)
                ->order('create_time desc')->page($page,$pageSize)->select();


        foreach ($list as &$one) {
            //发布者用户名字
            $one['admin_user_name'] = db('admin_user')->where('id=' . $one['user_id'])->value('true_name');
            //广告主
            $one['advertiser_name'] = get_college_info($one['advertiser_id'])['title'];

            //投放地区
            $allRegion = db('ad_province')->where('ad_id=' . $one['id'])->select();
            $region_name = '';
            if (count($allRegion) == 0) {
                $region_name = '全国';
            } else {
                $provinceIdArr = [];
                foreach ($allRegion as $v) {
                    $region_name .= ',' . get_region_name($v['province']);
                    $provinceIdArr[] = $v['province'];
                }
//                 aa($allRegion);
                $one['region_id'] = implode(',', $provinceIdArr);
                $one['region_name'] = substr($region_name, 1);
            }



            //广告对应的轮播图或者文章的详情
            if ($one['show_type'] == 1) {
                $contentModel = new SlideShow();
                $one['content'] = $contentModel->getOne($one['post_id']);
            } else {
                $contentModel = new Article();
                $one['content'] = $contentModel->getArticleForTermId($one['post_id']);
            }


        }

//         aa($list);
        if ($pageSize != 1) {
            $count = db('ad')->alias('a')->join('dd_ad_province b','a.id = b.ad_id','LEFT')->where($where)->count('distinct a.id');
            return ['count'=>$count,'pagesize'=>config('pagesize'), 'data'=>$list];
        } else {
            return $list[0];
        }


    }


    //
    /**
     * 外部接口调用广告的模型
     * @param int $province 广告投放所在的省份ID
     * @param int $show_type 展示方式，1：轮播图广告，2：文章广告
     * @param int $term_type 广告的终端载体，1：一体机，2：学生APP
     * @return array
     */
    public function apiList($where)
    {
        //$where = "a.status=1 AND release_status=2 AND show_type=$show_type AND term_type=$term_type AND b.province in (0, $province) ";

        $map = [];
        $map['a.status'] = 1;
        $map['release_status'] = 2;
        $map['show_type'] = key_exists('show_type', $where) ? $where['show_type'] : 1;
        $map['term_type'] = key_exists('term_type', $where) ? $where['term_type'] : 1;
        if (key_exists('category_id', $where) && $where['category_id']) {
            $map['category_id'] = $where['category_id'];
        }
        $map['b.province'] = ['in', [0,$where['province']]];

        $list = db('ad')
                ->alias('a')
                ->distinct(true)
                ->field('a.*,c.term_type')
                ->join('dd_ad_province b','a.id = b.ad_id','LEFT')
                ->join('dd_slide_show c','a.id = c.ad_id','INNER')
                ->where($map)
                ->order('create_time desc')
                ->select();
//         echo db('ad')->getLastSql();exit;


        $data = [];
        $i = 0;
        foreach ($list as &$one) {
            $contentModel = new SlideShow();
            $slide = $contentModel->getOne($one['post_id']);
            $data[$i]['title'] = $slide['title'];
            //对应的轮播图ID
            $data[$i]['slide_id'] = db('slide_show')->where('ad_id=' . $one['id'])->value('id');

            $data[$i]['image_url'] = $slide['image_url'];
            $data[$i]['jump_obj'] = $slide['jump_obj'];
            $data[$i]['obj_value'] = $slide['obj_value'];
            $data[$i]['create_time'] = $slide['create_time'];
            $data[$i]['is_ad'] = $one['id'];
            $data[$i]['have_ad_tag'] = $one['have_ad_tag'];
            //如果跳转是文章的话，文章要有原始标题
            if ($slide['jump_obj'] == 1) {
                $data[$i]['origin_title'] = db('article')->where('id='.$slide['obj_value'])->value('title');
            }

            $i++;
        }

//         aa($data);
        return $data;
    }


    //添加和编辑广告数据
    public function addAd($data)
    {
        //检查广告排期占用和投放区域占用
        $check = $this->checkValid($data);
        if (! $check) {
            return ['code'=>-1, 'msg'=>'这个时间段该区域已经安排了广告投放'];
        }

        //编辑
        if (key_exists('id', $data)) {
            $this->allowField(true)->save($data, ['id'=>$data['id']]);
            $adId = $data['id'];
            db('ad_province')->where('ad_id=' . $adId)->delete();
        } else { //添加
            $adId = db('ad')->insertGetId($data);
//             echo db('ad')->getLastSql();exit;
        }

        $provinceArr = explode(',', $data['province']);

        $regionData = [];

        $i = 0;
        foreach ($provinceArr as $one) {
            $regionData[$i] = ['ad_id'=>$adId, 'province'=>$one];
            $i++;
        }

        //更新投放区域
        db('ad_province')->insertAll($regionData);

        //如果是轮播图的广告，要把轮播图对应的ad_id字段
        if ($data['show_type'] == 1) {
            //编辑
            if (key_exists('id', $data)) {
                db('slide_show')->where('ad_id=' . $adId)->update(['ad_id'=>0]);
            }
            db('slide_show')->where('id=' . $data['post_id'])->update(['ad_id'=>$adId]);
        }

        //触发推送
        $pushModel = new Common();
        $pushModel->sendPushToR40('','update_banner');

        return $adId;
    }

    /*
     * 检查广告排期占用和投放区域占用，如果投放区域上，投放时间段有交叉的广告数量超过系统的设置那就不能通过检测
     */
    public function checkValid($data)
    {

        $showType = $data['show_type'];
        $adConfig = $this->getConfig($showType);
        $limit = $data['province'] == 0 ? $adConfig['country_count'] : $adConfig['province_count'];


        //判断广告日期是否重叠
        //$w = "((start_date <= '$sDate' and end_date >= '$sDate') OR (start_date <= '$eDate' and end_date >= '$eDate'))";
        $w = $this->timeOverlap($data);
        $where = "status=1 AND show_type=$showType AND province in (" . $data['province'] . ")  AND " . $w;


        //如果修改，把自己的ID排除在外
        if(key_exists('id', $data) && $data['id']) {
            $where .= " AND a.id != " . $data['id'];
        }

        $sql = "SELECT distinct a.id FROM dd_ad a LEFT JOIN dd_ad_province b on a.id=b.ad_id  WHERE $where";
//         echo $sql;exit;
        $ids = Db::query($sql);
        $count = count($ids);


//         echo $count;exit;
        if ($count >= $limit) {
            return false;
        } else {
            return true;
        }

    }

    //时间排期查看
    public function fromTime($data)
    {
        $showType = $data['show_type'];
        $adConfig = $this->getConfig($showType);

        $w = $this->timeOverlap($data);
        $where = "status=1 AND show_type=$showType AND " . $w . " AND b.id is NOT NUll";

        //获取当前时间下所有的省份ID
        $sql = "SELECT distinct a.province FROM dd_ad_province a LEFT JOIN dd_ad b on a.ad_id=b.id  WHERE $where";
//         echo $sql;exit;
        $provinceList = Db::query($sql);

        $data = [];
        $notSelect = [];
        $data['can_select'] = [];
        $data['expire'] = [];
        $data['not_select'] = [];

        foreach ($provinceList as $provinceId) {
            $provinceId = $provinceId['province'];

            $map = "status=1 AND show_type=$showType AND province=$provinceId AND b.id is NOT NUll";
            $adList = db('ad_province')->alias('a')->join('dd_ad b','a.ad_id = b.id','LEFT')->where($map)->select();
            $alreadyCount = count($adList);
            $provinceName = get_region_name($provinceId);
            $result = ['province'=>$provinceId, 'name'=>$provinceName];



            //获取不能发布广告的城市
            if ($provinceId == 0) {
                $diff = $adConfig['country_count'] - $alreadyCount;
                if ($diff <= 0) {
                    $notSelect[] = $provinceId;
                    $data['not_select'][] = $result;
                } else {
                    $data['can_select'][] = $result;
                }

            } else {
                $diff = $adConfig['province_count'] - $alreadyCount;
                if ($diff <= 0) {
                    $notSelect[] = $provinceId;
                    $data['not_select'][] = $result;
                }
            }


//             aa($adList);
            foreach ($adList as $row) {

                //倒计时30天
                $today = date('Y-m-d');
//                 aa($row['end_date']);
                $diffDay = diffBetweenTwoDays($today, $row['end_date']);

                if ($diffDay >= 0 && $diffDay <= 30) {
                    $result['days'] = $diffDay;
                    $data['expire'][] = $result;
                    break;
                }

            }
        }

        $allProvince = [];
        $allProvince['0'] = '全国';
        $allProvinceList = get_region_list();
        $allProvince = array_merge($allProvince, $allProvinceList);

        //不能发布广告的城市ID的一维数组
        foreach ($allProvince as $key=>$value) {
            if (! in_array($key, $notSelect)) {
                $data['can_select'][] = ['province'=>$key , 'name'=>$value];
            }
        }

        return $data;
    }


    //地区排期查看
    public function fromRegion($para)
    {
        $showType = $para['show_type'];
        $adConfig = $this->getConfig($showType);
        $provinceId = $para['province'];

        $thisMoth = $para['this_month'];
//         aa($para);
        $today = date('Y-m-d');
        $sDate = $thisMoth . '-01';
        $eDate = date('Y-m-d', strtotime("$sDate +1 month -1 day"));
        $countDay = diffBetweenTwoDays($sDate, $eDate);

        $w = $this->timeOverlap($para);

        $where = "status=1 AND show_type=$showType AND province=" . $para['province'] . " AND " . $w;

        //获取当前时间下所有的省份ID
        $sql = "SELECT start_date,end_date FROM dd_ad_province a LEFT JOIN dd_ad b on a.ad_id=b.id  WHERE $where";

        $dateList = Db::query($sql);
        $data = [];

        for ($i=0; $i<$countDay; $i++) {
            //如果当天已经过去了，那么是灰色的-1
            $day = sprintf("%02d", $i+1);
            $fullDay = $thisMoth . '-' . $day;

            $data[$i]['date'] = $day;
            if ($fullDay < $today) {
                $data[$i]['count'] = -1;
            } else {
                //当前在所取数据库时间区间列表匹配的次数
                $zoneCount = $this->timezoneCount($fullDay, $dateList);
                if ($provinceId == 0) {
                    $diff = $adConfig['country_count'] - $zoneCount;
                    //1代表这天的排期已经被占满, 2表示未占满，好看可以排期
                    $data[$i]['count'] = $diff <= 0 ? 0 : $diff;

                } else {
                    $diff = $adConfig['province_count'] - $zoneCount;
                    //1代表这天的排期已经被占满, 2表示未占满，好看可以排期
                    $data[$i]['count'] = $diff <= 0 ? 0 : $diff;
                }
            }

        }

        return $data;
    }


    //某一天在时间区间重合的次数
    private function timezoneCount($day, $zoneArr)
    {
        $count = 0;
        foreach ($zoneArr as $one) {
            if ($one['start_date'] <= $day && $one['end_date'] >= $day) {
                $count++;
            }
        }

        return $count;
    }


    //获取设定的广告投放数量的限制
    private function getConfig($showType)
    {
        $adConfig = db('ad_config')->where('show_type=' . $showType)->find();
        //如果没有设置，那么全国和省份都是默认99
        if (! $adConfig) {
            $adConfig['country_count'] = 99;
            $adConfig['province_count'] = 99;
        }

        return $adConfig;
    }

    //获取关联文章ID
    public function getAdPostId($province_id)
    {
        $where['show_type'] = 2;
        $where['status'] = 1;
        $time = strtotime(date('Y-m-d',time()));
        $where['UNIX_TIMESTAMP(end_date)'] = ['>=', $time];
        $where['UNIX_TIMESTAMP(start_date)'] = ['<=', $time];
        $p_where['province'] = $province_id;
        if (!$province_id) {
            $p_where['province'] = ['>=', 0];
        }
        $ad_ids = DB::name('AdProvince')->where($p_where)->whereOr(['province'=>0])->column('ad_id');
        $where['id'] = ['in', $ad_ids];
        $post_ids = DB::name('Ad')->where($where)->column('post_id');
        return $post_ids;
    }

}