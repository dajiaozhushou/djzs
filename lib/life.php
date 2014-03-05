<?php
//var_dump(tianqi());
function tianqi(){
  //获取大连天气数据
$content=file_get_contents("http://m.weather.com.cn/data/101070201.html");
  //json解析为数组
  $weather=json_decode($content,true);
  //组织数组
$item['Title']='【'.$weather[weatherinfo][city].'】'.'今天：'.$weather[weatherinfo][weather1].'气温：'.$weather[weatherinfo][temp1].' 风力'.$weather[weatherinfo][fl1];
$item['Description']='【'.$weather[weatherinfo][city].'】'.'今天：'.$weather[weatherinfo][weather1].' 风力'.$weather[weatherinfo][fl1];
  $item['PicUrl']='http://djzs.sinaapp.com/public/images/a'.$weather[weatherinfo][img1].'.jpg';
$item['Url']="";
$shuzu[1]=$item;
  
  $item['Title']='明天：'.$weather[weatherinfo][weather2].'气温：'.$weather[weatherinfo][temp2].' 风力'.$weather[weatherinfo][fl2];
$item['Description']='【'.$weather[weatherinfo][city].'】'.'明天：'.$weather[weatherinfo][weather2].' 风力'.$weather[weatherinfo][fl2];
  $item['PicUrl']='http://djzs.sinaapp.com/public/images/a'.$weather[weatherinfo][img3].'.jpg';
$item['Url']="";
$shuzu[2]=$item;
  
  $item['Title']='后天：'.$weather[weatherinfo][weather3].'气温：'.$weather[weatherinfo][temp3].' 风力'.$weather[weatherinfo][fl2];
$item['Description']='【'.$weather[weatherinfo][city].'】'.'后天：'.$weather[weatherinfo][weather3].' 风力'.$weather[weatherinfo][fl3];
  $item['PicUrl']='http://djzs.sinaapp.com/public/images/a'.$weather[weatherinfo][img5].'.jpg';
$item['Url']="";
$shuzu[3]=$item;
return $shuzu;
}
//weather天气

?>