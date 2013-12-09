<?php
/**
 * User: Mr.wang
 * Date: 13-11-25
 * Time: 下午9:51
 */

class WeChat {
    //与微信服务器通信URL
    public $URL='';
    //与微信服务器通信Token
    public $Token='';
    //微信应用AppI的
    public $AppId='';
    //微信应用AppSecret
    public $AppSecret='';
    public $getRequest;
    public $postStr;

    public $ToUserName = '';
    public $FromUserName = '';
    public $CreateTime = '';
    public $MsgType = '';
    public $Content = '';
    public $MsgId = '';
    public $PicUrl = '';
    public $MediaId = '';
    public $Format = '';
    public $ThumbMediaId = '';
    public $Location_X = '';
    public $Location_Y = '';
    public $Scale = '';
    public $Label = '';
    public $Title = '';
    public $Description = '';
    public $Url = '';
    public $Event = '';
    public $EventKey = '';
    public $Ticket = '';
    public $Latitude = '';
    public $Longitude = '';
    public $Precision = '';
    public $Recognition = '';
    //构造方法
    public function  __construct(){

    }
    //设置应用信息
    public function setApp($AppId,$AppSecret){
        $this->AppId = $AppId;
        $this->AppSecret = $AppSecret;
    }
    //设置通信密钥及URL
    public function setToken($URL,$Token){
        $this->URL = $URL;
        $this->Token = $Token;
    }
    /**
     * 以下部分为获取各种消息方法
     */
    //获取Token
    public function getToken(){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->AppId.'&secret='.$this->AppSecret;
        require_once dirname(__FILE__).'/lib/class.Http.php';
        //$msg =  json_decode(Http::makeRequest($url,'','','get','https')['msg'],true);
        return $msg['access_token'];
    }
    //将来自外部的请求转化为数组
    public function getRequest(){
        $this->postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if(!empty($this->postStr)){
            $this->getRequest = (array)simplexml_load_string($this->postStr,'SimpleXMLElement',LIBXML_NOCDATA);
        }
        return $this;
    }
    //获取消息类型
    public function getType(){
        $this->MsgType = $this->getRequest['MsgType'];
    }
    //获取消息发送者
    public function getFrom(){
        $this->FromUserName = $this->getRequest['FromUserName'];
    }
    //获取消息接收者
    public function getTo(){
        $this->ToUserName = $this->getRequest['ToUserName'];
    }
    //获取消息时间
    public function getTime(){
        $this->CreateTime = $this->getRequest['CreateTime'];
    }
    //获取消息内容
    public function getContent(){
        $this->Content = $this->getRequest['Content'];
    }
    //获取消息id
    public function getId(){
        $this->MsgId = $this->getRequest['MsgId'];
    }
    //获取图片链接
    public function getPicUrl(){
        $this->PicUrl = $this->getRequest['PicUrl'];
    }
    //获取媒体Id
    public function getMediaId(){
        $this->MediaId = $this->getRequest['MediaId'];
    }
    //获取消息附件格式（暂时仅有语音格式）
    public function getFormat(){
        $this->Format = $this->getRequest['Format'];
    }
    //获取视频消息缩略图的媒体Id
    public function getThumbMediaId(){
        $this->ThumbMediaId = $this->getRequest['ThumbMediaId'];
    }
    //获取地理位置纬度
    public function getLocation_X(){
        $this->Location_X = $this->getRequest['Location_X'];
    }
    //获取地理位置经度
    public function getLocation_Y(){
        $this->Location_Y = $this->getRequest['Location_Y'];
    }
    //获取地图缩放大小
    public function getScale(){
        $this->Scale = $this->getRequest['Scale'];
    }
    //获取地理位置信息
    public function getLabel(){
        $this->Label = $this->getRequest['Label'];
    }
    //获取消息标题
    public function getTitle(){
        $this->Title = $this->getRequest['Title'];
    }
    //获取消息描述
    public function getDescription(){
        $this->Description = $this->getRequest['Description'];
    }
    //获取消息链接
    public function getUrl(){
        $this->Url = $this->getRequest['Url'];
    }
    //获取事件类型
    public function getEvent(){
        $this->Event = $this->getRequest['Event'];
    }
    //获取事件KEY值
    public function getEventKey(){
        $this->EventKey = $this->getRequest['EventKey'];
    }
    //获取二维码的Ticket
    public function getTicket(){
        $this->Ticket = $this->getRequest['Ticket'];
    }
    //获取上报地理位置事件纬度
    public function getLatitude(){
        $this->Latitude = $this->getRequest['Latitude'];
    }
    //获取上报地理位置事件经度
    public function getLongitude(){
        $this->Longitude = $this->getRequest['Longitude'];
    }
    //获取上报地理位置事件精度
    public function getPrecision(){
        $this->Precision = $this->getRequest['Precision'];
    }
    //获取语音识别结果
    public function getRecognition(){
        $this->Recognition = $this->getRequest['Recognition'];
    }
    //根据消息调用相关方法设置变量
    public function getData(){
        $this->getRequest();
        $this->getType();
        $type = $this->MsgType;
        switch($type){
            case 'text':
                $this->getTo();
                $this->getFrom();
                $this->getTime();
                $this->getContent();
                $this->getId();
                break;
            case 'image':
                $this->getTo();
                $this->getFrom();
                $this->getTime();
                $this->getPicUrl();
                $this->getMediaId();
                $this->getId();
                break;
            case 'voice':
                $this->getTo();
                $this->getFrom();
                $this->getTime();
                $this->getMediaId();
                $this->getFormat();
                $this->getId();
                $this->getRecognition();
                break;
            case 'video':
                $this->getTo();
                $this->getFrom();
                $this->getTime();
                $this->getMediaId();
                $this->getThumbMediaId();
                $this->getId();
                break;
            case 'location':
                $this->getTo();
                $this->getFrom();
                $this->getTime();
                $this->getLocation_X();
                $this->getLocation_Y();
                $this->getScale();
                $this->getLabel();
                $this->getId();
                break;
            case 'link':
                $this->getTo();
                $this->getFrom();
                $this->getTime();
                $this->getTitle();
                $this->getDescription();
                $this->getUrl();
                $this->getId();
                break;
            //接收事件推送
            case 'event':
                $this->getEvent();
                $event=$this->Event;
                switch($event){
                    //菜单点击事件
                    case 'CLICK':
                        $this->getTo();
                        $this->getFrom();
                        $this->getTime();
                        $this->getEventKey();
                        break;
                    //用户订阅事件(包含扫描二维码进行订阅)
                    case 'subscribe':
                        $eventkey = $this->getEventKey();
                        if(empty($eventkey)){
                        $this->getTo();
                        $this->getFrom();
                        $this->getTime();
                        }
                        else{
                            $this->getTo();
                            $this->getFrom();
                            $this->getTime();
                            $this->getTicket();
                        }
                        break;
                    //用户取消订阅事件
                    case 'unsubscribe':
                        $this->getTo();
                        $this->getFrom();
                        $this->getTime();
                        break;
                    //扫描带参数二维码事件
                    case 'scan':
                        $this->getTo();
                        $this->getFrom();
                        $this->getTime();
                        $this->getEventKey();
                        $this->getTicket();
                        break;
                    //上报地理位置事件
                    case 'LOCATION':
                        $this->getTo();
                        $this->getFrom();
                        $this->getTime();
                        $this->getLatitude();
                        $this->getLongitude();
                        $this->getPrecision();
                        break;

                }
                break;
        }
    }

    /**
     * 各种消息获取方法完毕
     */
    /**
     * 以下为回复各种消息方法
     */
    //设置消息是否星标
    public function set_funcflag() {
        $this->funcflag = true;
    }
    //回复文本消息
    public function replyText($content){
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";
        echo sprintf($textTpl,$this->FromUserName,$this->ToUserName,time(),'text',$content);
    }
    //回复图片消息
    public function replyImage($MediaId){
        $imageTpl="<xml>
                   <ToUserName><![CDATA[%s]]></ToUserName>
                   <FromUserName><![CDATA[%s]]></FromUserName>
                   <CreateTime>%s</CreateTime>
                   <MsgType><![CDATA[%s]]></MsgType>
                   <Image>
                   <MediaId><![CDATA[%s]]></MediaId>
                   </Image>
                   </xml>";
        echo sprintf($imageTpl,$this->FromUserName,$this->ToUserName,time(),'image',$MediaId);
    }
    //回复语音消息
    public function replyVoice($MediaId){
        $voiceTpl="<xml>
                   <ToUserName><![CDATA[%s]]></ToUserName>
                   <FromUserName><![CDATA[%s]]></FromUserName>
                   <CreateTime>%s</CreateTime>
                   <MsgType><![CDATA[%s]]></MsgType>
                   <Voice>
                   <MediaId><![CDATA[%s]]></MediaId>
                   </Voice>
                   </xml>";
        echo sprintf($voiceTpl,$this->FromUserName,$this->ToUserName,time(),'voice',$MediaId);
    }
    //回复视频消息
    public function replyVideo($MediaId,$ThumbMediaId){
        $videoTpl="<xml>
                   <ToUserName><![CDATA[%s]]></ToUserName>
                   <FromUserName><![CDATA[%s]]></FromUserName>
                   <CreateTime>%s</CreateTime>
                   <MsgType><![CDATA[%s]]></MsgType>
                   <Video>
                   <MediaId><![CDATA[%s]]></MediaId>
                   <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                   </Video>
                   </xml>";
        echo sprintf($videoTpl,$this->FromUserName,$this->ToUserName,time(),'video',$MediaId,$ThumbMediaId);
    }
    //回复音乐消息
    public function replyMusic($Title,$Description,$MusicURL,$HQMusicUrl,$ThumbMediaId){
        $musicTpl="<xml>
                   <ToUserName><![CDATA[%s]]></ToUserName>
                   <FromUserName><![CDATA[%s]]></FromUserName>
                   <CreateTime>%s</CreateTime>
                   <MsgType><![CDATA[%s]]></MsgType>
                   <Music>
                   <Title><![CDATA[%s]]></Title>
                   <Description><![CDATA[%s]]></Description>
                   <MusicUrl><![CDATA[%s]]></MusicUrl>
                   <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                   <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                   </Music>
                   </xml>";
        echo sprintf($musicTpl,$this->FromUserName,$this->ToUserName,time(),'music',$Title,$Description,$MusicURL,$HQMusicUrl,$ThumbMediaId);
    }
    //回复图文消息
    public function replyNews($items){
        $itemTpl="<item>
                  <Title><![CDATA[%s]]></Title>
                  <Description><![CDATA[%s]]></Description>
                  <PicUrl><![CDATA[%s]]></PicUrl>
                  <Url><![CDATA[%s]]></Url>
                  </item>";
        $articles = '';
        foreach ($items as $key){
            $articles.=sprintf($itemTpl,$key['Title'],$key['Description'],$key['PicUrl'],$key['Url']);
        }
        $newsTpl="<xml>
                  <ToUserName><![CDATA[%s]]></ToUserName>
                  <FromUserName><![CDATA[%s]]></FromUserName>
                  <CreateTime>%s</CreateTime>
                  <MsgType><![CDATA[%s]]></MsgType>
                  <ArticleCount><![CDATA[%s]]></ArticleCount>
                  <Articles>%s</Articles>
                  </xml> ";
        echo sprintf($newsTpl,$this->FromUserName,$this->ToUserName,time(),'news',count($items),$articles);
    }
//消息真实性校验
    public function valid() {
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    public function checkSignature() {
        $args = array("signature", "timestamp", "nonce");
        foreach ($args as $arg)
            if (!isset($_GET[$arg]))
                return false;

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $tmpArr = array($this->Token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}

