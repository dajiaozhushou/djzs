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
	
		<div data-role="page" data-theme="a">
			<div data-role="header" data-position="inline">
				<h1>感谢使用大交助手</h1>
			</div>
			<div data-role="content" data-theme="a">
				
	
<?php
$openid=$_GET["openid"];
require_once dirname(__FILE__).'/common/config.php';
connect();
$sql="select * from users where openid='$openid'";
$res=mysql_query($sql);
$result=mysql_fetch_assoc($res);
if(!$result){
	echo '非法进入，如有疑问请发送邮件至"admin@djtuhome.com"';
	exit();
}
else {
  if(isset($_POST["submit"])){
      require_once dirname(__FILE__).'/modules/jwzx/jwzx.class.php';
    $aa=new jwzx($openid);
	echo $aa->getChengjiByTerm($_POST["year"],$_POST["term"]);
    exit();
}
echo'
<form action="chengji.php?openid='.$openid.'" method="post">
<div>请选择学期查看成绩</div>
<table cellpadding="0" cellspacing="0" >
<tr>
<th colspan="2" class="login_title"></th>
</tr>
<tr>
<th >学年</th>
<td >
<select name="year">  
   <option value="">全部</option>
   <option value="34">2014</option> 
   <option value="33">2013</option> 
   <option value="32">2012</option>
   <option value="31">2011</option>
   <option value="30">2010</option>
   <option value="29">2009</option>
   <option value="28">2008</option> 
   <option value="27">2007</option>
   <option value="26">2006</option>
   <option value="25">2005</option>
   <option value="24">2004</option> 
   <option value="23">2003</option>
   <option value="22">2002</option>
</select>
</td>

</tr>
<tr>
  
<th>学期</th>
<td>
<select name="term"  >  
   <option value="">全部</option> 
   <option value="1">春</option> 
   <option value="2">秋</option>
</select>
</td>

</tr>
<td style="padding-left:15px;">
<input type="submit" tabindex="3" name="submit" value="查询" class="button">
</td>
</table>
</form>
';
}
              ?>

				<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>	</div>
		</div>
	</body>
</html>