<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>感谢使用大交助手</title>
        <link rel="stylesheet" href="public/css/B.min.css" />
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile.structure-1.3.0.min.css" />

	</head>
	<body>	
		<div data-role="page" >
			<div data-role="header" data-position="inline">
				<h1>感谢使用大交助手</h1>
			</div>
			<div data-role="content">
				
	
<?php
$openid=$_GET["openid"];
require_once dirname(__FILE__).'/common/config.php';
connect();
$sql="select * from users where openid='$openid'";
$res=mysql_query($sql);
$result=mysql_fetch_assoc($res);
if(!$result){
	echo '非法进入，请尝试重新关注，如还不正常请发送邮件“绑定失败”至"admin@djtuhome.com,"';
	exit();
}
else {
  if(isset($_POST["submit"])){
	bangding($openid,$_POST["username"],$_POST["password"]);
      
    exit();
}
echo'
<form action="bdjw.php?openid='.$openid.'" method="post">
<div>绑定教务管理系统(期间修改密码需要重新绑定，请注意保管您的密码)</div>
<table cellpadding="0" cellspacing="0" >
<tr>
<th colspan="2" class="login_title"></th>
</tr>
<tr>
<th >用户名</th>
<td ><input type="text" name="username" class="input" tabindex="1"></td>
<td style="padding-left:15px;">
<input type="submit" tabindex="3" name="submit" value="绑定" class="button">
</td>
</tr>
<tr>
<th>密&nbsp;&nbsp;码</th>
<td><input type="password" name="password" class="input" tabindex="2"></td>
</tr>
</table>
</form>
';

}


function bangding($openid,$username,$password){
	$login_url		=	'http://jw.djtu.edu.cn/academic/j_acegi_security_check';
			$post_fields	=	'j_username='.$username.'&j_password='.$password;
			$ch = curl_init($login_url);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_BODY, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
			$header = curl_exec($ch);
			curl_close($ch);
			preg_match('/Location:(.*);/', $header, $Location);
			if($Location[1]==" http://jw.djtu.edu.cn/academic/common/security/login.jsp"){
			
      echo '绑定失败，密码错误或教务服务器不给力，请尝试<a data-ajax="false" href="bdjw.php?openid='.$openid.'">重新绑定</a>';
	}
	else{
        preg_match('/jsessionid=(.*)\r/', $header, $arr);
        $url='http://jw.djtu.edu.cn/academic/student/currcourse/currcourse.jsdo?year=33&term=1';
            $ch = curl_init($url);
			curl_setopt($ch, CURLOPT_BODY, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 0);
       		 $headera[]= "Cookie:JSESSIONID=$arr[1]";
             curl_setopt($ch,CURLOPT_HTTPHEADER,$headera); 
			$content = curl_exec($ch);
			curl_close($ch);
    require_once dirname(__FILE__).'/lib/jiequ.php';
	$ft["title"]["begin"]='id=';       //截取的开始点
	$ft["title"]["end"]='&yearid';        //截取的结束点
	$th["title"]["百度"]="千度";      //截取部分的替换
    $rs=pick($content,$ft,$th);                //开始采集
    $jwid=$rs["title"];  
        
      require_once dirname(__FILE__).'/common/config.php';
		connect();
		$sql="replace INTO `jwzx`(`openid`, `username`, `password`,`jwid`) VALUES ('$openid','$username','$password','$jwid')";
		mysql_query($sql);
		if(mysql_affected_rows()){
	    echo "恭喜绑定成功，您可以回复“帮助”使用更多高级功能，教务在线相关功能有时缓慢，因为教务服务器原因，请辛苦多发几次就OK^^,好东西别忘分享给同学们哦";}
	}
  curl_close($ch);
}?>
				<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>	</div>
		</div>
	</body>
</html>