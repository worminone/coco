<?php
/*
 * 所有对内对外的功能接口的父类
 * 1、如果是网页前端的ajax请求，就做跨域的安全检查。
 * 2、如果是原生app和php代码的接口调用，就必须做验签校验。
*/

namespace app\common\controller;

use think\Request;
use think\Db;

class Base extends \think\Controller
{
    protected $termType;
    protected $appType;

    public function __construct()
    {
        //vue框架有用OPTIONS方法发送预请求，忽略该请求
        if (Request::instance()->isOptions()) {
            $this->myHeaders();
            exit('The request for the options method of the HTTP protocol is ignored');
        }

        parent::__construct();
        @define('MODULE_NAME', strtolower(Request::instance()->module() ));
        @define('CONTROLLER_NAME', strtolower(Request::instance()->controller() ));
        @define('ACTION_NAME', strtolower(Request::instance()->action() ));

        $origin = key_exists('HTTP_ORIGIN', $_SERVER) ? $_SERVER['HTTP_ORIGIN'] : '';
        // 手机端请求和大道教育总后台的请求
        if ($origin == '') {
            $this->termType = 'app';
            $this->appCheck();

        } else {   // 网页端请求
            $this->termType = 'web';
            $origin = parse_url($origin, PHP_URL_HOST);
            $this->webCheck($origin);
        }

    }


    //输出header
    protected function myHeaders()
    {
        $allowHeaders = 'Origin, X-Requested-With, If-Modified-Since, Content-Type, Accept, token, api-version';
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE,OPTIONS');
        header('Access-Control-Allow-Headers: ' . $allowHeaders);
        header('Content-type: application/json;charset=utf-8');
    }


    // 网页端api请求认证，源域名认证，不做签名验证
    public function webCheck($origin)
    {
        $allowOrignConfig = config('allow_orgin');
        // aa($allowOrignConfig);
        if (config('my_env') == 'myself' || config('my_env') == 'dev' || config('my_env') == 'test' ) {
            $this->myHeaders();
        } elseif (in_array($origin, $allowOrignConfig)) {
            $this->myHeaders();
        } else {
            $this->response(403, '403 forbidden');
            //header('Access-Control-Allow-Origin: *');
        }
    }

    // 原生APP的api请求验证签名
    public function appCheck()
    {
        //非浏览器请求都有验证签名，就不需要做源域名检测
        $this->myHeaders();

        $sign = input('sign');
        $para = input('post.') ? : input('get.');

        $uri = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        $freePass = config('free_pass');
        //全部转换成小写
        $freePass = array_map('strtolower', $freePass);

        $env = config('my_env');

        //指定的方法不做任何验签
        if ($freePass && !in_array($uri, $freePass)) {
            //开发者本地开发环境和公司内外开发环境不做签名验证
            if ($env !== 'dev' && $env !== 'myself') {
                if (! $this->checkNewSign($sign, $para)) {
                    //$this->response(-99, "sign签名验证失败！");
                }
            }
        }
    }


    // 验证签名
    public function checkNewSign($sign, $data)
    {
        if (!$sign) {
            return false;
        }

        if (!$data) {
            return false;
        }

        if ($sign == make_sign($data)) {
            return true;
        }

        return false;
    }

    // 生成查询的缓存key
    public function setCacheKey($no_cache_key = array())
    {
        $apiUrl = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        $para = input('request.');
        unset($para['time']);
        unset($para['sign']);
        unset($para['token']);
        foreach ($no_cache_key as $one) {
            unset($para[$one]);
        }

        $paraUrl = http_build_query($para);

        $key = md5($apiUrl . '?' . $paraUrl);

        return $key;
    }


    protected function response($code = 1, $msg = '', $body = array(), $ext = array())
    {

        $data = array();
        $data['code'] = $code;
        $data['msg'] = $msg;

        $data['data'] = $body;

        if (!empty($ext)) {
            $data = array_merge($data, $ext);
        }

        if ($this->termType == 'web') {
            //$callback = input('callback');
            // echo $callback;exit;
            // exit($callback . '(' . json_encode($data, JSON_UNESCAPED_UNICODE) . ')');
            $str = json_encode($data, JSON_UNESCAPED_UNICODE);
            echo str_replace("null", '""',$str);
            exit();
        } else {
            $str = json_encode($data, JSON_UNESCAPED_UNICODE);
            echo str_replace("null", '""',$str);
            exit();
        }
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    public function ajaxReturn($code = 1, $msg = '', $bady = [], $type='',$json_option=0) {
        if(empty($type)) $type  =   'JSON';
        $data['code'] = $code;
        $data['msg'] = $msg;
        $data['data'] = $bady;
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET['callback']) ? $_GET['callback'] : 'jsonpReturn';

                exit($handler.'('.json_encode($data,$json_option).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default     :
                // 用于扩展其他返回格式数据
        }
    }

    //列表分页
    public function getPageList($table, $where, $order, $field, $limit)
    {
        $page = input('param.page', '1', 'intval');
        if (empty($limit)) {
            $limit = config('paginate.list_rows');
        }
        if (empty($field)) {
            $field = '*';
        }
        $count = Db::name($table)
            ->where($where)
            ->count();
        $page_num = ceil($count / $limit);
        $list = Db::name($table)
            ->field($field)
            ->where($where)
            ->limit($limit * ($page - 1), $limit)
            ->order($order)
            ->select();
//         echo Db::name($table)->getLastSql();
        if (!empty($list)) {
            $data = [
                'count' => $count,
                'page_num' => $page_num,
                'page' => $page,
                'pagesize' => $limit,
                'list' => $list
            ];
        } else {
            $data = [
                'count' => 0,
                'page_num' => $page_num,
                'page' => $page,
                'pagesize' => $limit,
                'list' => []
            ];
        }

        return $data;
    }

}
