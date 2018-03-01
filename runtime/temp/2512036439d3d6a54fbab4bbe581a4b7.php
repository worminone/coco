<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"F:\wamp64\www\ddzx_admin_api\trunk\public/../application/api\view\Aio\Article.html";i:1508924772;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="/static/css/editor.css" />
<style type="text/css">
	*{margin:0px;padding:0px}
	body{background:#f7f7f7}
	.erro{width:100%;height:197px;background:url("http://image.zgxyzx.net/user.png") no-repeat center;display:block;margin:180px auto 20px auto}
	.txt{width:100%;height:30px;text-align:center;line-height:30px;font-size:26px;color:#8b8b8b}
</style>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
	    $("#img").error(function () {  
	        $(this).attr('src', "/static/css/loading.png");
	    });  
	});  
</script>

<?php if(!empty($info['title'])): ?>
<title><?php echo $info['title']; ?></title>
</head>
	<body style="background-color:f2f2f2;height: 100%; font-family: 'Microsoft YaHei' ! important; padding:0; margin:0;">
		<div style="text-align: center;"> 
			<img src="<?php echo $info['img']; ?>" style="width: 1200px; height:600px;">
		</div>
		<div style="margin: 0px auto; width:1200px;background-color:#fff;" >
			<div style="text-align: center;width: 100%;font-size: 36px;color: #3a3a3a; padding-top: 60px;font-weight:600">
				<?php echo $info['title']; ?>
			</div>
			<div style="margin-top: 48px;height:120px ">
				<span style="float: left;margin:0 0 0 40px;font-size: 22px;color: #989898;height: 60px;line-height: 60px;display:flex;">
					<img id='img' src="<?php echo $info['avatar']; ?>" style="width:60px;height: 60px;margin-right: 12px">
					<span>作者:    <?php echo $info['username']; ?></span>
				</span>
				<span style="float: right;margin-right: 40px;font-size: 22px;color: #989898;margin:15px 40px 0 0;">阅读量:
					<span style="color:#88cbff;"><?php echo $info['view_count']; ?></span>
				</span>
				<span style="float: right;margin-right: 40px;font-size: 22px;color: #989898;margin:15px 40px 0 0;">发布时间:<?php echo $info['publish_time']; ?></span>

			</div>
			<div class='ql-editor' style="padding:0 40px 40px;margin: 0 0 40px 0;font-size: 24px;color: #353535; word-break: break-word;"><?php echo $info['content']; ?></div>
		</div>
		
	</body>
<?php else: ?>
	<div class="erro"></div>
	<div class="txt">暂无数据</div>
<?php endif; ?>
</html>