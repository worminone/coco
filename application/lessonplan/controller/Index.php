<?php
namespace app\lessonplan\controller;

use think\Db;
use app\common\controller\Base;
use app\common\controller\Admin;

class Index extends Base
{
    /**
     * @api {post} /lessonplan/Index/getIndexList 首页展示
     * @apiVersion 1.0.0
     * @apiName getIndexList
     * @apiGroup Index
     * @apiDescription 首页展示
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg  成功的信息和失败的具体信息.
     */
    public function getIndexList()
    {
        $data['data1']=array(
            array(
                'title'=>'信息化教案管理>教案目录管理',
                'content'=>'创建课本目录',
            ),
            array(
                'title'=>'信息化教案管理>教案内容管理',
                'content'=>'选择目录进行添加内容；（每添加一个内容则生成一个对应的二维码，可用于学生app扫码）',
            ),
            array(
                'title'=>'内容资源管理',
                'content'=>'上传基础教学资源（视频、作业、文件、图片(仅用于作业的附件)...）；课本上的内容可使用以上形式展现给用户',
            ),
            array(
                'title'=>'信息化教案管理>教案内容管理',
                'content'=>'选择目录进行添加内容>选择具体内容；将之前的内容格子进行对应的教学资源填充',
            ),
            array(
                'title'=>'信息化教案管理>教学备课管理',
                'content'=>'选择第二层级（即生涯课本的主题）进行展示图片配置、教师备课说明内容编辑、教学文档选择',
            ),
            array(
                'title'=>'教案套餐>套餐管理',
                'content'=>'新建套餐：对教案目录进行选择打包成套餐；查看套餐：查看打包的套餐有哪些目录；套餐列表：所有教学套餐都展示在列表上，若修改套餐内容会影响已经销售出去的学校',
            ),
            array(
                'title'=>'教案套餐>套餐销售管理',
                'content'=>'列表：展示已经购买了套餐的学校；销售套餐：已经购买了生涯教学套餐的学校再次进行配置后，购买校方的师生可以查看/使用此套餐',
            )
        );
        $data['data2']=array(
            array(
                'content'=>'<i class="el-icon-information" style="color:#ff5722"></i>&nbsp&nbsp上传的内容资源的本地文件名一定要规范，符合教师教学时展示用',
            ),
            array(
                'content'=>'<i class="el-icon-information" style="color:#ff5722"></i>&nbsp&nbsp教案内容：<br>&nbsp&nbsp&nbsp&nbsp1.内容格子的二维码用于课本印刷后(不能删除！)(不能删除！)(不能删除！)<br>&nbsp&nbsp&nbsp&nbsp2.格子内的教学资源可以替换',
            ),
            array(
                'content'=>'<i class="el-icon-information" style="color:#ff5722"></i>&nbsp&nbsp删除目录需要：<br>&nbsp&nbsp&nbsp&nbsp1.未设置教学套餐 <br>&nbsp&nbsp&nbsp&nbsp2.无子目录 <br>&nbsp&nbsp&nbsp&nbsp3.无教案内容',
            ),
            array(
                'content'=>'<i class="el-icon-information" style="color:#ff5722"></i>&nbsp&nbsp删除教学套餐需要<br>&nbsp&nbsp&nbsp&nbsp1.套餐销售管理未设置购买学校',
            ),
            array(
                'content'=>'<i class="el-icon-information" style="color:#ff5722"></i>&nbsp&nbsp套餐销售管理移除学校后<br>&nbsp&nbsp&nbsp&nbsp1.该学校教师无法查看套餐内容<br>&nbsp&nbsp&nbsp&nbsp2.该学校学生无法查看/扫码该套餐内容',
            ),
        );
        $this->response(1, '获取成功', $data);
    }
    /**
     * @api {post} /lessonplan/Index/getIndex 首页注意事项
     * @apiVersion 1.0.0
     * @apiName getIndex
     * @apiGroup Index
     * @apiDescription 首页注意事项
     *
     * @apiParam {String} token 用户的token.
     * @apiParam {String} time 请求的当前时间戳.
     * @apiParam {String} sign 签名.
     *
     * @apiSuccess {Int} code 错误代码，1是成功，-1是失败.
     * @apiSuccess {String} msg 成功的信息和失败的具体信息.
     */
    public function getIndex()
    {
        $data=array(
            array(
                'title'=>'<i class="el-icon-information" style="color:#ffc107"></i>上传的内容资源的本地文件名一定要规范，符合教师教学时展示用',
                'content'=>'',
            ),
            array(
                'title'=>'<i class="el-icon-information" style="color:#ffc107"></i>教案内容：',
                'content'=>'&nbsp&nbsp&nbsp&nbsp1.内容格子的二维码用于课本印刷后(不能删除！)(不能删除！)(不能删除！)<br>
                            &nbsp&nbsp&nbsp&nbsp2.格子内的教学资源可以替换',
            ),
            array(
                'title'=>'<i class="el-icon-information" style="color:#ffc107"></i>删除目录需要：',
                'content'=>'&nbsp&nbsp&nbsp&nbsp1.未设置教学套餐 <br>&nbsp&nbsp&nbsp&nbsp2.无子目录 <br>&nbsp&nbsp&nbsp&nbsp3.无教案内容',
            ),
            array(
                'title'=>'<i class="el-icon-information" style="color:#ffc107"></i>删除教学套餐需要',
                'content'=>'&nbsp&nbsp&nbsp&nbsp1.套餐销售管理未设置购买学校',
            ),
            array(
                'title'=>'<i class="el-icon-information" style="color:#ffc107"></i>套餐销售管理移除学校后',
                'content'=>'&nbsp&nbsp&nbsp&nbsp1.该学校教师无法查看套餐内容<br>&nbsp&nbsp&nbsp&nbsp2.该学校学生无法查看/扫码该套餐内容',
            ),
        );
        $this->response(1, '获取成功', $data);
    }

}