<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//当月第一天及最后一天
function getthemonth($date)
{
    $firstday = date('Y-m-01', strtotime($date));
    $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
    return array($firstday,$lastday);
}


/**
 * 求两个日期之间相差的天数
 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
 * @param string $day1
 * @param string $day2
 * @return number
 */
function diffBetweenTwoDays ($day1, $day2)
{
    $second1 = strtotime($day1);
    $second2 = strtotime($day2);

    return ($second2 - $second1) / 86400 + 1;
}


/**
 * 生成某个范围内的随机时间，用来生成测试数据
 * @param <type> $begintime  起始时间 格式为 Y-m-d
 * @param <type> $endtime    结束时间 格式为 Y-m-d
 */
function random_date($begintime, $endtime) {
    $begin = strtotime($begintime);
    $end = strtotime($endtime);
    $timestamp = rand($begin, $end);
    // d($timestamp);
    return date("Y-m-d", $timestamp);
}


//获取高校信息
function get_college_info($college_id)
{
    $options = [
        'type' => 'File',
        'expire' => 0,
        'path' => APP_PATH . 'runtime/cache/',
    ];

    cache($options);
    $key = 'college_info_' . $college_id;
    $data = cache($key);
    if (!$data) {
        $api = config('college_api') . '/index/CollegeAdmin/getCollegeInfoById';
        $param = [];
        $param['college_id'] = $college_id;
        $return = curl_api($api, $param, 'post', 0);
        $data = $return['data'];
        cache($key, $data);
    }

    return $data;

}


//获取地区列表,$pid=0是所有省份
function get_region_list($pid=0)
{
    $options = [
        'type' => 'File',
        'expire' => 0,
        'path' => APP_PATH . 'runtime/cache/',
    ];

    cache($options);
    $key = 'regin_list_data';
    $data = cache($key);
    if (!$data) {
        $api = config('base_api') . '/api/region/region';
        $param = [];
        $param['pid'] = 0;
        $param['type'] = 2;
        $return = curl_api($api, $param, 'post');
        $data = $return['data'];
        cache($key, $data);
    }

    return $data;

}

//通过接口获取地区名称，缓存所有
function get_region_name($region_id)
{
    if ($region_id == 0) {
        return '全国';
    }

    $options = [
        'type' => 'File',
        'expire' => 0,
        'path' => APP_PATH . 'runtime/cache/',
    ];

    cache($options);
    $key = 'regin_data' . $region_id;
    $data = get_region_list() ? : cache($key);
    if (!$data) {
        $api = config('base_api') . '/api/region/region?pid=-1&type=2';
        $return = curl_api($api, []);
        $data = $return['data'];
        cache($key, $data);
    }

    return key_exists($region_id, $data) ? $data[$region_id] : '';

}


/**
 * +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
 * +----------------------------------------------------------
 *
 * @param string $len
 *            长度
 * @param string $type
 *            字串类型
 *            0 字母 1 数字 其它 混合
 * @param string $addChars
 *            额外字符
 *            +----------------------------------------------------------
 * @return string +----------------------------------------------------------
 */

function rand_string($len = 8, $type = '', $addChars = '')
{
    $str = '';
    switch ($type) {
        case 0:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 1:
            $chars = str_repeat('0123456789', 3);
            break;
        case 2:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 3:
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 4:
            // @codingStandardsIgnoreStart
            $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借" . $addChars;
            // @codingStandardsIgnoreEnd
            break;
        case 5:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' . $addChars;
            break;
        default:
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            // $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
            $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZ0123456789' . $addChars;
            break;
    }
    if ($len > 10) { // 位数过长重复字符串一定次数
        $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
    }
    if ($type != 4) {
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
    } else {
        // 中文随机字
        for ($i = 0; $i < $len; $i++) {
            // $str.= self::msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
            $str .= substr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
        }
    }
    return $str;
}


//产生token
function make_token($uid)
{
    $token = sha1($uid . date('Ymd') . microtime(true));
    $token .= uniqid();

    return $token;
    // return 'token:' . $token;
}

/*
 * 生产url签名
 */
function make_sign($data)
{
    if (empty($data)) {
        return false;
    }
    ksort($data);
    $sign_data = array();
    foreach ($data as $key => $val) {
        if (!is_array($val) && $val !== null && !in_array($key, array(
                'sign'
            ))
        ) {
            $sign_data[] = $key . '=' . $val;
        }
    }
    return md5(implode('&', $sign_data) . config('sign_key'));
}

// api请求封装
function curl_api($api, $paraArr, $method = 'get', $is_debug = 0)
{
    $paraArr['time'] = time();
    $paraArr['sign'] = make_sign($paraArr);

    $headers = [];
    if (key_exists('token', $paraArr)) {

        $headers = array(
                'token: ' . $paraArr['token'],
        );
    }

//     $paraArr['admin_key'] = 'Jd8234Ojd1ZHuw98WhsI1298JXn94';
    $paras = http_build_query($paraArr);
    //$logPath = 'Runtime/' . date('Y-m-d') . '.api.log';

    $ch = curl_init();
    if ($method == 'get') {
        $url = $api . '?' . $paras;
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paras);
    }

    if ($is_debug) {
        echo $api . '?' . $paras;
        exit();
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);      //是否返回头部信息
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);

    $data = curl_exec($ch);

//     $debug = curl_getinfo($ch, CURLINFO_HEADER_OUT);
//     aa($debug);

    curl_close($ch);
    $data = @json_decode($data, true);
    return $data;
}

// 手机号码正则
function test_mobile($mobile)
{
    $re = '/^1\d{10}$/';
    return preg_match($re, $mobile);
}

// 用户名的正则表达式,用户名最短3个字符最长不超过16个字符,
function test_username($username)
{
    $re = '/^[a-zA-Z0-9_]{3,16}$/';
    return preg_match($re, $username);
}

/**
 * 系统邮件发送函数
 *
 * @param string $tomail
 *            接收邮件者邮箱
 * @param string $name
 *            接收邮件者名称
 * @param string $subject
 *            邮件主题
 * @param string $body
 *            邮件内容
 * @param string $attachment
 *            附件列表
 * @param array $copy_to
 *            抄送人邮件地址数组
 * @return boolean
 * @author static7 <static7@qq.com>
 */
function send_mail($tomail, $name, $subject = '', $body = '', $attachment = null, $copy_to = array())
{
    $mail = new \PHPMailer(); // 实例化PHPMailer对象
    $mail->CharSet = 'UTF-8'; // 设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug = 1; // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
    $mail->SMTPAuth = true; // 启用 SMTP 验证功能
    // $mail->SMTPSecure = 'ssl'; // 使用安全协议

    $mail->Host = "smtp.mxhichina.com";
    $mail->Port = 25;
    $mail->Username = "jiankong@dadaodata.com";
    $mail->Password = "Ddzx2016!@#";

    $mail->SetFrom('jiankong@dadaodata.com');
    $replyEmail = ''; // 留空则为发件人EMAIL
    $replyName = ''; // 回复名称（留空则为发件人名称）
    // $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    if ($copy_to) {
        foreach ($copy_to as $one) {
            $mail->addCC($one);
        }
    }
    $mail->AddAddress($tomail, $name);
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}

function my_upload_image($image_path, $upload_url)
{
    $obj = new CurlFile($image_path);
    // 必须指定文件类型，否则会默认为application/octet-stream，二进制流文件
    $obj->setMimeType("image/jpeg");
    $post['file'] = $obj;
    $post['abc'] = "abc";
    // var_dump($post);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, false);
    // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_URL, $upload_url); // 上传类

    $info = curl_exec($ch);
    aa($info);

    // curl_close($ch);
    // var_dump($info);
    // file_put_contents('./1.html',$info);
    // $res=json_decode($info,true);
    // //var_dump($res);
}

function dd()
{
    $arr = func_get_args();
    echo '<pre>';
    foreach ($arr as $v) {
        print_r($v);
    }
    echo '</pre>';
    exit();
}

// 高榕的调试
function aa($var)
{
    print_r($var);
    // var_dump($var);
    exit();
}

/**
 * 数组 转 对象
 *
 * @param array $arr
 *            数组
 * @return object
 */
function objectToArray($e)
{
    $e = (array)$e;
    foreach ($e as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $e[$k] = (array)objectToArray($v);
        }
    }
    return $e;
}

/**
 * 简单对称加密算法之加密
 *
 * @param String $string
 *            需要加密的字串
 * @param String $skey
 *            加密EKY
 * @author Anyon Zou <zoujingli@qq.com>
 * @date 2013-08-13 19:30
 * @update 2014-10-10 10:10
 * @return String
 */
function encode_ddzx($string = '', $skey = 'DDZX2017')
{
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value) {
        $key < $strCount && $strArr[$key] .= $value;
    }
    return str_replace(array(
        '=',
        '+',
        '/'
    ), array(
        'O0O0O',
        'o000o',
        'oo00o'
    ), join('', $strArr));
}

/**
 * 简单对称加密算法之解密
 *
 * @param String $string
 *            需要解密的字串
 * @param String $skey
 *            解密KEY
 * @author Anyon Zou <zoujingli@qq.com>
 * @date 2013-08-13 19:30
 * @update 2014-10-10 10:10
 * @return String
 */
function decode_ddzx($string = '', $skey = 'DDZX2017')
{
    $strArr = str_split(str_replace(array(
        'O0O0O',
        'o000o',
        'oo00o'
    ), array(
        '=',
        '+',
        '/'
    ), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value) {
        $key <= $strCount && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    }
    return base64_decode(join('', $strArr));
}

/**
 * 获取客户端IP地址
 *
 * @param integer $type
 *            返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv
 *            是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = null;
    if (null !== $ip) {
        return $ip[$type];
    }

    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) {
                unset($arr[$pos]);
            }

            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    // $long = sprintf("%u", ip2long($ip));
    // $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    // return $ip[$type];

    return $ip;
}

/**
 * 得到分页limit
 * @param $page
 * @param $size
 * @return string
 */
function getLimit($page, $size)
{
    $start = ($page - 1) * $size;
    $limit = $start . ',' . $size;
    return $limit;
}

function is_json($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * 发送数据
 * @param String $url 请求的地址
 * @param Array $header 自定义的header数据
 * @param Array $content POST的数据
 * @return String
 */
function tocurlArray($url, $data)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $return = curl_exec($ch);
    curl_close($ch);

//     echo $return;exit;
    if (is_json($return)) {
        return json_decode($return, true);
    } else {
        return $return;
    }
}

/**
 * 二维码生成器
 * @param string $url //网址
 * @param int $w //大小
 * @param bool $logo_img //是否中间有logo
 * @param string $el //纠错级别
 */
function getQRCode($url = '', $w = 10, $logo_img = false, $el = 'h')
{
    vendor("phpqrcode.phpqrcode");
    // 将纠错级别转成大写
    $el = strtoupper($el);
    // 1)判断是否有logo需要加载
    if ($logo_img !== false) {
        // 先生成二维码
        QRcode::png($url, 'qrcode/qrcode.png', $el, $w, 2);
        // 创建大画布
        $qr = imagecreatefromstring(file_get_contents('qrcode/qrcode.png'));
        // 创建小画布
        $logo = imagecreatefromstring(file_get_contents($logo_img));

        // 获取大画布的宽高
        list($qr_w, $qr_h) = getimagesize('qrcode/qrcode.png');
        // 获取小画布的宽高
        //list($logo_w, $logo_h) = getimagesize($logo_img);
        $logo_w = 600;
        $logo_h = 600;

        // 创建空白画布
        // 定义logo最终的宽高,为了让logo能够自适应
        // logo覆盖的面积为二维码的三分之一
        $width = $qr_w / 3;
        $height = $qr_h / 3;

        $white_logo = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($white_logo, 255, 255, 255);
        imagefill($white_logo, 0, 0, $white);

        // 等比例缩放logo
        $ratio = $logo_w / $logo_h;
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }


        // 重新采样
        imagecopyresampled($qr, $logo, ($qr_w - $width) / 2, ($qr_h - $height) / 2, 0, 0, $width, $height, $logo_w,
            $logo_h);


        // 输出图片
        Header("Content-type: image/png");
        /**
         * 图片输出,二选一,gd库自定义的函数imagepng()或者
         * 像PHPQRCode类库一样使用ImagePng()这在Windows系统下没有问题
         * 但是移植到了Linux系统下,问题就暴露了,严格区分大小写
         *
         */
        imagepng($qr);

        // 销毁画布
        imagedestroy($qr);
        imagedestroy($logo);
        imagedestroy($white_logo);

    } else {
        // 没有logo的情况
        return QRcode::png($url, false, $el, $w, 2);
    }

}

function exportWord($fileName, $PHPWord)
{
    vendor("phpword.PHPWord");
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition:attachment;filename=" . $fileName . ".docx");
    header('Cache-Control: max-age=0');
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
    $objWriter->save('php://output');
}

/**
 * 二维数组根据字段进行排序
 * @params array $array 需要排序的数组
 * @params string $field 排序的字段
 * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 */
function arraySequence($array, $field, $sort = 'SORT_DESC')
{
    $arrSort = array();
    foreach ($array as $uniqid => $row) {
        foreach ($row as $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($sort), $array);
    return $array;
}

//php把时间戳转换成多少分钟前
function wordTime($time)
{
    $time = (int)substr($time, 0, 10);
    $int = time() - $time;
    $str = '';
    if ($int <= 2) {
        $str = sprintf('刚刚', $int);
    } elseif ($int < 60) {
        $str = sprintf('%d秒前', $int);
    } elseif ($int < 3600) {
        $str = sprintf('%d分钟前', floor($int / 60));
    } elseif ($int < 86400) {
        $str = sprintf('%d小时前', floor($int / 3600));
    } elseif ($int < 2592000) {
        $str = sprintf('%d天前', floor($int / 86400));
    } else {
        $str = date('Y-m-d H:i:s', $time);
    }
    return $str;
}

/**
 * 根据文件扩展名得到默认封面图片
 * @param $ext
 */
function getCoverByExt($ext)
{
    $fileArray = array(
        'ppt' => 'ppt',
        'pptx' => 'ppt',
        'doc' => 'word',
        'docx' => 'word',
        'pdf' => 'pdf',
        'xlsx' => 'exl',
        'xls' => 'exl'
    );
    if (!empty($fileArray[$ext])) {
        return 'http://image.zgxyzx.net/' . $fileArray[$ext] . '.png';
    }
    return '';
}

/**
 * 得到默认封面图片
 */
function getDefaultPic()
{
    return 'http://' . $_SERVER['SERVER_NAME'] . '/static/default.png';
}

/*
 * 获得总后台图片地址
 */
function getGZPic($url)
{
    if (preg_match('/(http:\/\/)|(https:\/\/)/i', $url)) {
        return $url;
    } else {
        return config('gz_api') . $url;
    }
}

/**
 * 计算开机时长
 * @param $data
 * @return string
 */
function caculateSurvival($data)
{
    if (empty($data['mac_address']) || !isLive($data)) {
        return '0小时';
    }
    if (!empty($data['startup_time'])) {
        $start_time = strtotime($data['startup_time']);
        $startup_interval_time = time() - $start_time;
    } else {
        $first_refresh_time = strtotime($data['first_refresh_time']);
        $startup_interval_time = time() - $first_refresh_time;
    }

    if ($startup_interval_time < 60*60) {
        $msg = "小于1小时";
    } else {
        $hour = floor($startup_interval_time/(60*60));
        $msg = $hour."小时";
    }
    return $msg;
}

function isLive($data)
{
    if (empty($data['refresh_time'])) {
        $live = 0;
    } else {
        $refresh_time = strtotime($data['refresh_time']);
        $refresh_interval_time = time() - $refresh_time;
        if ($refresh_interval_time <= 60*30) {
            $live = 1;
        } else {
            $live = 0;
        }
    }
    return $live;
}


/*
 * 导出excel
 */
function exportExcel($expTitle, $expCellName, $expTableData)
{
    $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
    $fileName = $xlsTitle;
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);
    //vendor("phpexcel.PHPExcel");

    $objPHPExcel = new \PHPExcel();
    $cellName = array(
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z',
        'AA',
        'AB',
        'AC',
        'AD',
        'AE',
        'AF',
        'AG',
        'AH',
        'AI',
        'AJ',
        'AK',
        'AL',
        'AM',
        'AN',
        'AO',
        'AP',
        'AQ',
        'AR',
        'AS',
        'AT',
        'AU',
        'AV',
        'AW',
        'AX',
        'AY',
        'AZ'
    );
    for ($i = 0; $i < $cellNum; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '1', $expCellName[$i][1]);
    }
    // Miscellaneous glyphs, UTF-8
    for ($i = 0; $i < $dataNum; $i++) {
        for ($j = 0; $j < $cellNum; $j++) {
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 2),
                $expTableData[$i][$expCellName[$j][0]]);
        }
    }
    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
    header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

/**
 * php实现下载远程文件保存到本地
 **
 * $url 文件所在地址
 * $path 保存文件的路径
 * $filename 文件自定义命名
 * $type 使用什么方式下载
 * 0:curl方式,1:readfile方式,2file_get_contents方式
 *
 * return 文件名
 */
function downloadFile($url, $path = '', $filename = '', $type = 0)
{
    if ($url == '') {
        return false;
    }
    //获取远程文件数据
    if ($type === 0) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);//最长执行时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);//最长等待时间

        $img = curl_exec($ch);
        curl_close($ch);
    }
    if ($type === 1) {
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
    }
    if ($type === 2) {
        $img = file_get_contents($url);
    }
    //判断下载的数据 是否为空 下载超时问题
    if (empty($img)) {
        return false;
    }

    //没有指定路径则默认当前路径
    if ($path === '') {
        $path = "./";
    }
    //如果命名为空
    if ($filename === "") {
        $filename = md5($img) . time();
    }
    //获取后缀名
    $ext = substr($url, strrpos($url, '.'));
    if ($ext && strlen($ext) < 5) {
        $filename .= $ext;
    }

    //防止"/"没有添加
    $path = rtrim($path, "/") . "/";
    //var_dump($path.$filename);die();
    $fp2 = @fopen($path . $filename, 'a');

    fwrite($fp2, $img);
    fclose($fp2);
    //echo "finish";
    return $filename;
}

/**
 * 根据excel文件地址返回文件数据
 * @param $filePath 文件地址
 * @return array|string 返回数据数组
 */
function importExcel($filePath)
{
    $result = array();
    if (!file_exists($filePath)) {
        return array("error" => 0, 'message' => 'file not found!');
    }
    /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
    $PHPReader = new \PHPExcel_Reader_Excel2007();
    if (!$PHPReader->canRead($filePath)) {
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($filePath)) {
            return 'no Excel';
        }
    }
    $PHPExcel = $PHPReader->load($filePath);
    /**读取excel文件中的第一个工作表*/
    $currentSheet = $PHPExcel->getSheet(0);
    /**取得最大的列号*/
    $allColumn = $currentSheet->getHighestColumn();
    /**取得一共有多少行*/
    $allRow = $currentSheet->getHighestRow();
    for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
        $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, 1)->getValue();
        $result['title'][ord($currentColumn) - 65] = $val;
    }
    /**从第二行开始输出，因为excel表中第一行为列名*/
    for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
        /**从第A列开始输出*/
        for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
            $cell = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow);
            $val = $cell->getValue();
            //处理时间
//             if ($cell->getDataType() == \PHPExcel_Cell_DataType::TYPE_NUMERIC) {
//                 $cellstyleformat = $cell->getParent()->getStyle($cell->getCoordinate())->getNumberFormat();
//                 $formatcode = $cellstyleformat->getFormatCode();
//                 if (preg_match('/^(\[\$[A-Z]*-[0-9A-F]*\])*[hmsdy]/i', $formatcode)) {
//                     $val = gmdate("Y-m-d H:i:s", \PHPExcel_Shared_Date::ExcelToPHP($val));
//                 } else {
//                     $val = \PHPExcel_Style_NumberFormat::toFormattedString($val, $formatcode);
//                 }
//             }
            $result['data'][$currentRow - 1][ord($currentColumn) - 65] = $val;
        }
    }
    if (!empty($result['data'])) {//过滤空行
        foreach ($result['data'] as $key => $valueList) {
            if (is_array($valueList)) {
                $flag = false;
                foreach ($valueList as $value) {
                    if (!empty($value)) {
                        $flag = true;
                        break;
                    }
                }
                if (!$flag) {
                    unset($result['data'][$key]);
                }
            }
        }
    }
    return $result;
}
