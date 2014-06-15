<?php
header("Content-type: text/html; charset=utf-8");
$appid = '';
$appsecret = '';
$tokenarray=json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret),ture);
$token=$tokenarray["access_token"];
echo $token;
define("ACCESS_TOKEN",$token);

//创建菜单
function createMenu($data){
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $tmpInfo = curl_exec($ch);
 if (curl_errno($ch)) {
  return curl_error($ch);
 }
 curl_close($ch);
 return $tmpInfo;
}

//获取菜单
function getMenu(){
 return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN);
}
//删除菜单
function deleteMenu(){
 return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN);
}
 
 
$data = '{
     "button":[
     {
           "name":"教务查询",
           "sub_button":[
			{
               "type":"click",
               "name":"今天课表",
               "key":"kebiao"
            },
            {
               "type":"click",
               "name":"明天课表",
               "key":"mingtiankebiao"
            },
			{
               "type":"click",
               "name":"成绩查询",
               "key":"chengji"
            },
			{
               "type":"click",
               "name":"考试安排",
               "key":"kaoshi"
            },
			{
               "type":"view",
               "name":"学籍管理",
               "url":"http://mp.weixin.qq.com/s?__biz=MjM5MjI1MTcwMQ==&mid=200023729&idx=2&sn=56b1e8b1b217bc6ef83bb5ad289acdc8#rd"
            }]
       },
{
           "name":"学习资源",
           "sub_button":[
			{
               "type":"view",
               "name":"IT创新基地",
               "url":"http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5MjI1MTcwMQ==&appmsgid=10043754&itemidx=2&sign=507e1de3897acf62d3cc42e00a1a6dde#wechat_redirect"
            }]
 }
      ,
	   {
           "name":"多彩交大",
           "sub_button":[
			{
               "type":"click",
               "name":"街景校园",
               "key":"jjxy"
            },
        	{
               "type":"click",
               "name":"天气预报",
               "key":"tianqi"
            },
			{
               "type":"view",
               "name":"常见问题",
               "url":"http://mp.weixin.qq.com/s?__biz=MjM5MjI1MTcwMQ==&mid=200023729&idx=1&sn=2a2d6df567d1baf178b4fc4e4b0f41bd"
            }]
       }';
 

createMenu($data);
echo getMenu();
//echo deleteMenu();