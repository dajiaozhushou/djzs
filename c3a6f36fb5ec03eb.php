<?php
require_once dirname(__FILE__) . '/wxlib/wechat.class.php';
$obj = new WeChat();
/*
 * 首次配置使用 $obj->Token='c3a6f36fb5ec03eb'; $obj->valid();
 */
$obj->getData();
/*******************特殊事件处理部分开始********************/
//订阅事件
if ($obj->Event == 'subscribe') {
    require_once 'common/config.php';
    connect();
    $sql = "replace INTO `users`(`openid`,`mode`) VALUES ('{$obj->FromUserName}',0)";
    mysql_query($sql);
    $obj->replyText('感谢关注大交助手^^消息指令代号如下
    【1】今天课表
    【2】明天课表
    【3】成绩查询
    【4】天气查询
    【5】街景校园[荐]
    【6】考试安排
    【7】学习生活导航
    【8】常见问题
    【0】帮助菜单
    <a href="http://djzs.sinaapp.com/bdjw.php?openid='.$obj->FromUserName.'">点击绑定教务在线使用全部功能</a>
    备:当前为新版测试中，请将您发现的bug和不足发送邮件至 admin@djtuhome.com，感谢您的使用^^
    ');
}
//帮助菜单
if ($obj->Content == '帮助' || $obj->Content == '0') {
    $obj->replyText('感谢关注大交助手^^消息指令代号如下
    【1】今天课表
    【2】明天课表
    【3】成绩查询
    【4】天气查询
    【5】街景校园[荐]
    【6】考试安排
    【7】学习生活导航
    【8】常见问题
    【0】帮助菜单
    <a href="http://djzs.sinaapp.com/bdjw.php?openid='.$obj->FromUserName.'">点击绑定教务在线使用全部功能</a>
    备:当前为新版测试中，请将您发现的bug和不足发送邮件至 admin@djtuhome.com，感谢您的使用^^
    ');
}
/*******************特殊事件处理部分结束********************/
/*******************教务功能部分开始********************/
// 查成绩
if ($obj->Content == '查成绩' || $obj->Content == '成绩' || $obj->EventKey == 'chengji'|| $obj->Content == '3') {
    require_once dirname(__FILE__) . '/modules/jwzx/jwzx.class.php';
    $aa = new jwzx($obj->FromUserName);
    $res = $aa->getChengji();
    $obj->replyText($res.'<a href="http://djzs.sinaapp.com/chengji.php?openid='.$obj->FromUserName.'">点击查看更多成绩</a>');
}
//查课表
if ($obj->Content == '查课表' || $obj->Content == '课表' || $obj->EventKey == 'kebiao'|| $obj->Content == '1') {
    $weekarray=array("日","一","二","三","四","五","六");
    require_once dirname(__FILE__) . '/modules/jwzx/jwzx.class.php';
    $aa = new jwzx($obj->FromUserName);
    $shuzu = $aa->getKebiao();
    if(!is_array($shuzu)){$obj->replyText($shuzu);}
    $zhou=date('W',time())-9;
    //$zhou='N';
    $d = date("w");
    $xingqi=$weekarray[$d];
    $tuwen[0]['Title'] = "第{$zhou}教学周周{$xingqi}";
    $tuwen[1]['Title'] = '【上午1、 2节】'."\n". $shuzu[$d][1];
    $tuwen[2]['Title'] = '【上午3、 4节】'."\n". $shuzu[$d][2];
    $tuwen[3]['Title'] = '【下午5、 6节】'."\n". $shuzu[$d][3];
    $tuwen[4]['Title'] = '【下午7、 8节】'."\n". $shuzu[$d][4];
    $tuwen[5]['Title'] = '【晚上9、10节】'."\n" . $shuzu[$d][5];
    $tuwen[6]['Title'] = '点击查看全部课表';
    $tuwen[6]['Url']='http://djzs.sinaapp.com/kebiao.php?openid='.$obj->FromUserName;
    $obj->replyNews($tuwen);
}
//明天课表
if ($obj->Content == '查明天课表' || $obj->Content == '明天课表'|| $obj->EventKey == 'mingtiankebiao'|| $obj->Content == '2') {
    $weekarray=array("日","一","二","三","四","五","六");
    require_once dirname(__FILE__) . '/modules/jwzx/jwzx.class.php';
    $aa = new jwzx($obj->FromUserName);
    $shuzu = $aa->getKebiao();
    if(!is_array($shuzu)){$obj->replyText($shuzu);}
    $zhou=date('W',time()+86400)-9;
    //$zhou='N';
    $d = date("w",time()+86400);
    $xingqi=$weekarray[$d];
    $tuwen[0]['Title'] = "第{$zhou}教学周周{$xingqi}";
    $tuwen[1]['Title'] = '【上午1、 2节】'."\n" . $shuzu[$d][1];
    $tuwen[2]['Title'] = '【上午3、 4节】'."\n" . $shuzu[$d][2];
    $tuwen[3]['Title'] = '【下午5、 6节】'."\n" . $shuzu[$d][3];
    $tuwen[4]['Title'] = '【下午7、 8节】'."\n" . $shuzu[$d][4];
    $tuwen[5]['Title'] = '【晚上9、10节】'."\n" . $shuzu[$d][5];
    $tuwen[6]['Title'] = '点击查看全部课表';
    $tuwen[6]['Url']='http://djzs.sinaapp.com/kebiao.php?openid='.$obj->FromUserName;
    $obj->replyNews($tuwen);
}
//查考试安排
if ($obj->Content == '考试安排' || $obj->Content == '考试'|| $obj->EventKey == 'kaoshi'|| $obj->Content == '6') {
    require_once dirname(__FILE__) . '/modules/jwzx/jwzx.class.php';
    $aa = new jwzx($obj->FromUserName);
    $exam = $aa->getKaoshi();
    if(!is_array($exam)){$obj->replyText($exam);}
    for($i=2;$i<count($exam)-1;$i++){
    $kaoshianpai .= "课程:".$exam[$i][1]."\n".'时间:'.$exam[$i][2]."\n地点:".$exam[$i][3]."\n\n";
		}
  if($kaoshianpai==""){$kaoshianpai="暂无考试安排\n";}
  $obj->replyText($kaoshianpai."大交助手祝您考试顺利!");
}
//学籍管理相关
if ($obj->Content == '学籍管理' || $obj->Content == '学籍'|| $obj->Content == '7') {
    $obj->replyText('<a href="http://mp.weixin.qq.com/s?__biz=MjM5MjI1MTcwMQ==&mid=200023729&idx=2&sn=56b1e8b1b217bc6ef83bb5ad289acdc8#rd">点击查看学籍管理</a>');
}
//绑定教务
if ($obj->Content == '绑定教务' || $obj->Content == '8' || $obj->Content == '绑定教务在线') {
    $obj->replyText('<a href="http://djzs.sinaapp.com/bdjw.php?openid='.$obj->FromUserName.'">点击绑定教务在线</a>');
}
/*****************************************************/
/**************************多彩交大部分开始***************************/
//天气
if ($obj->Content=="天气" || $obj->Content=="4" || $obj->EventKey=="tianqi" ){
  require_once dirname(__FILE__).'/lib/life.php';
  $obj->replyNews(tianqi());
}
//街景校园
if ($obj->Content=="街景校园" || $obj->Content=="街景" || $obj->EventKey=="jjxy" || $obj->Content == '5'){
  require_once dirname(__FILE__).'/lib/jiejing.php';
  $obj->replyNews(jiejing());
}
//常见问题
if ($obj->Content=="问题" || $obj->Content=="常见问题" ||  $obj->Content == '8'){
  $obj->replyText('<a href="http://mp.weixin.qq.com/s?__biz=MjM5MjI1MTcwMQ==&mid=200023729&idx=1&sn=2a2d6df567d1baf178b4fc4e4b0f41bd">点击查看常见问题</a>');
}
/**************************多彩交大部分结束****************************/
else{
    $obj->replyText('暂不能识别您的消息，已转人工处理^^消息指令代号如下
    【1】今天课表
    【2】明天课表
    【3】成绩查询
    【4】天气查询
    【5】街景校园[荐]
    【6】考试安排
    【7】学习生活导航
    【8】常见问题
    【0】帮助菜单
    <a href="http://djzs.sinaapp.com/bdjw.php?openid='.$obj->FromUserName.'">点击绑定教务在线使用全部功能</a>
    备:当前为新版测试中，请将您发现的bug和不足发送邮件至 admin@djtuhome.com，感谢您的使用^^
    ');
}