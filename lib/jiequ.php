<?php

//获取匹配内容
function fetch_match_contents($begin,$end,$c)
{
	$begin=change_match_string($begin);
	$end=change_match_string($end);
	if(@preg_match("/{$begin}(.*?){$end}/i",$c,$rs))
	{return $rs[1];}
	else {return "";}
}//转义正则表达式字符串
function change_match_string($str){
	//注意，以下只是简单转义
	$old=array("/","$");
	$new=array("\/","\$");
	$str=str_replace($old,$new,$str);
	return $str;
}

//采集网页
function pick($content,$ft,$th)
{
	$c=$content;
	foreach($ft as $key => $value)
	{
	 $rs[$key]=fetch_match_contents($value["begin"],$value["end"],$c);
	 if(is_array($th[$key]))
	 { foreach($th[$key] as $old => $new)
	 {
	  $rs[$key]=str_replace($old,$new,$rs[$key]);
	 }
	 }
	}
	return $rs;
}
