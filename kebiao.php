<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>全部课表</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Site CSS -->
  <link href="http://lib.sinaapp.com/js/bootstrap/v3.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php
function getAllKebiao($openid){
require_once dirname(__FILE__) . '/modules/jwzx/jwzx.class.php';
    $aa = new jwzx($openid);
    $shuzu = $aa->getKebiao();
    return $shuzu;
}
$all=getAllKebiao($_GET['openid']);
if(!is_array($all)){
    exit($all);
}
function style(){
    $array=array('active','success','warning','danger');
    $a=rand(0,3);
    return $array[$a];
}
echo "<table class=\"table table-condensed\"><th>全部课表</th><th>周一</th><th>周二</th><th>周三</th><th>周四</th><th>周五</th><th>周六</th><th>周日</th>
			 <tr><td class=\"active\">上午1、2节</td><td class="; echo style(); echo ">{$all[1][1]}</td><td class="; echo style(); echo ">{$all[2][1]}</td><td class="; echo style(); echo ">{$all[3][1]}</td><td class="; echo style(); echo ">{$all[4][1]}</td><td class="; echo style(); echo ">{$all[5][1]}</td><td class="; echo style(); echo ">{$all[6][1]}</td><td class="; echo style(); echo ">{$all[7][1]}</td></tr>
             <tr><td class=\"active\">上午3、4节</td><td class="; echo style(); echo ">{$all[1][2]}</td><td class="; echo style(); echo ">{$all[2][2]}</td><td class="; echo style(); echo ">{$all[3][2]}</td><td class="; echo style(); echo ">{$all[4][2]}</td><td class="; echo style(); echo ">{$all[5][2]}</td><td class="; echo style(); echo ">{$all[6][2]}</td><td class="; echo style(); echo ">{$all[7][2]}</td></tr>
             <tr><td class=\"active\">下午5、6节</td><td class="; echo style(); echo ">{$all[1][3]}</td><td class="; echo style(); echo ">{$all[2][3]}</td><td class="; echo style(); echo ">{$all[3][3]}</td><td class="; echo style(); echo ">{$all[4][3]}</td><td class="; echo style(); echo ">{$all[5][3]}</td><td class="; echo style(); echo ">{$all[6][3]}</td><td class="; echo style(); echo ">{$all[7][3]}</td></tr>
             <tr><td class=\"active\">下午7、8节</td><td class="; echo style(); echo ">{$all[1][4]}</td><td class="; echo style(); echo ">{$all[2][4]}</td><td class="; echo style(); echo ">{$all[3][4]}</td><td class="; echo style(); echo ">{$all[4][4]}</td><td class="; echo style(); echo ">{$all[5][4]}</td><td class="; echo style(); echo ">{$all[6][4]}</td><td class="; echo style(); echo ">{$all[7][4]}</td></tr>
             <tr><td class=\"active\">晚上9、10节</td><td class="; echo style(); echo ">{$all[1][5]}</td><td class="; echo style(); echo ">{$all[2][5]}</td><td class="; echo style(); echo ">{$all[3][5]}</td><td class="; echo style(); echo ">{$all[4][5]}</td><td class="; echo style(); echo ">{$all[5][5]}</td><td class="; echo style(); echo ">{$all[6][5]}</td><td class="; echo style(); echo ">{$all[7][5]}</td></tr>
	</table>";