<?php
/**
 * Created by Mr.wang
 * Date: 13-12-1
 * Time: 下午5:36
 */
class jwzx {
    private $username;
    private $password;
    private $jwid;
    private $sessionid;
    private $auth;
    function __construct($openid){
        require_once dirname(__FILE__).'/../../common/config.php';
        connect();
        $sql="select username,password,jwid from jwzx where openid='$openid'";
        $res=mysql_query($sql);
        $result=mysql_fetch_assoc($res);
        //var_dump($result);
        $this->username=$result["username"];
        $this->password=$result["password"];
        $this->jwid=$result["jwid"];
    }
    //验证是否成功绑定教务在线
    function auth(){
        if (!$this->username){
            $this->auth =  '您还没有绑定教务，请回复“绑定教务”绑定';
        }
        else {
            $login_url		=	'http://jw.djtu.edu.cn/academic/j_acegi_security_check';
			$post_fields	=	'j_username='.$this->username.'&j_password='.$this->password;
			$ch = curl_init($login_url);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_BODY, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
			$header = curl_exec($ch);
			curl_close($ch);
			preg_match('/Location:(.*);/', $header, $Location);
			if($Location[1]!=" http://jw.djtu.edu.cn/academic/common/security/login.jsp"){
			preg_match('/jsessionid=(.*)\r/', $header, $arr);
			//SAE不支持本地IO故从cookie中读出jsessionid写入header作登录验证
			$this->sessionid = $arr[1];
                return true;
			}
			else {
				$this->auth =  '登录错误，请重试,如果您修改了密码请回复"绑定教务"重新绑定';
			}
        }
    }
    //查课表
    function getKebiao(){
        if($this->auth()){
            $url='http://jw.djtu.edu.cn/academic/manager/coursearrange/showTimetable.do?id='.$this->jwid.'&yearid=34&termid=1&timetableType=STUDENT&sectionType=COMBINE';
            //$post_fields='year=32&term=1&para=0';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $header[]= "Cookie:JSESSIONID=$this->sessionid"; 
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header); 
            $table=curl_exec($ch);
            $array=get_td_array($table);
            $str=var_export($array,true);
            $str=str_replace(iconv('utf-8','gbk','讲课学时'),"\n",$str);
            $kebiao=eval('return '.iconv('gbk','utf-8',$str).';');
            for ($d=1;$d<=6;$d++){
            	$shuzu[$d][1]=$kebiao[4][$d];
            	$shuzu[$d][2]=$kebiao[5][$d];
            	$shuzu[$d][3]=$kebiao[6][$d];
            	$shuzu[$d][4]=$kebiao[7][$d];
            	$shuzu[$d][5]=$kebiao[8][$d];
            }
            $shuzu[0][1]=$kebiao[4][7];
            $shuzu[0][2]=$kebiao[5][7];
            $shuzu[0][3]=$kebiao[6][7];
            $shuzu[0][4]=$kebiao[7][7];
            $shuzu[0][5]=$kebiao[8][7];
            return $shuzu;
            curl_close($ch);

        }
        else{
            return $this->auth;
        }

    }
    //查成绩
    function getChengji(){
        if($this->auth()){
            $url='http://jw.djtu.edu.cn/academic/manager/score/studentOwnScore.do?groupId=&moduleId=2020';
            $post_fields='year=33&term=2&para=0';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            $header[]= "Cookie:JSESSIONID=$this->sessionid"; 
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header); 
            $table=curl_exec($ch);
            $array=get_td_array($table);
            $shu=count($array);
			$chengji='';
            for($i=2;$i<$shu;$i++){
                $array2=preg_replace("'([\r\n])[\s]+'", "", $array[$i]);
                $chengji.=$array2[4].$array2[10]."\n";
            }
            if ($chengji==""){
                $chengji="本学期还没有考试成绩或者您没有评教哦";
            }
            return $chengji;
            curl_close($ch);

        }
        else{
            return $this->auth;
        }
    }
    //按学期查成绩
    function getChengjiByTerm($year,$term){
        if($this->auth()){
            $url='http://jw.djtu.edu.cn/academic/manager/score/studentOwnScore.do?groupId=&moduleId=2020';
            $post_fields='year='.$year.'&term='.$term.'&para=0';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            $header[]= "Cookie:JSESSIONID=$this->sessionid"; 
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header); 
            $table=curl_exec($ch);
            $xuefenji=xuefenji($table);
            $array=get_td_array($table);
            $shu=count($array);
            $chengji='<table><tr><td>课程</td><td>平时</td><td>期末</td><td>总评</td></tr>';
            for($i=2;$i<$shu;$i++){
                $array2=preg_replace("'([\r\n])[\s]+'", "", $array[$i]);
                $chengji.='<tr><td>【'.$array2[4].'】</td><td>'.$array2[8].'</td><td>'.$array2[9].'</td><td>'.$array2[10].'</td></tr>';
            }
            $chengji .='</table>';
            $url='http://jw.djtu.edu.cn/academic/manager/score/studentOwnScore.do?groupId=&moduleId=2020';
            $post_fields='year='.$year.'&term='.$term.'&para=0&maxStatus=1';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            $header[]= "Cookie:JSESSIONID=$this->sessionid";
             curl_setopt($ch,CURLOPT_HTTPHEADER,$header); 
            $table=curl_exec($ch);
            $xuefenji=xuefenji($table);
            if ($chengji==""){
                $chengji="本学期还没有考试成绩或者您没有评教哦";
            }
            return '平均学分绩:'.$xuefenji.'<br />'.$chengji;
            curl_close($ch);

        }
        else{
            return $this->auth;
        }
    }
    //查考试时间
    function getKaoshi(){
        if($this->auth()){

            $cookie_file	=	tempnam(SAE_TMP_PATH,'cookie');
            $login_url		=	'http://jw.djtu.edu.cn/academic/j_acegi_security_check';
            $post_fields	=	'j_username='.$this->username.'&j_password='.$this->password;

            $ch = curl_init($login_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
            curl_exec($ch);
            curl_close($ch);

            $url='http://jw.djtu.edu.cn/academic/student/exam/index.jsdo';
            //$url='http://jw.djtu.edu.cn/academic/manager/examstu/studentQueryAllExam.do';
            //$post_fields='year=32&term=1&para=0';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $header[]= "Cookie:JSESSIONID=$this->sessionid";
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header); 
            $table=curl_exec($ch);
            $array=get_td_array($table);
            $str=var_export($array,true);
            $exam=eval('return '.iconv('gbk','utf-8',$str).';');
            curl_close($ch);
            return $exam;
        }
        else{
            return $this->auth;
        }
    }
}
//求平均学分绩
function xuefenji($content){
//$content=file_get_contents("1.txt");
    $key=get_td_array($content);
    for($i=2;$i<=count($key)-1;$i++){
        if($key[$i][10]=='合格'){$key[$i][10]=80;}
        if($key[$i][10]=='不合格'){$key[$i][10]=35;}
        if($key[$i][10]=='优' || $key[$i][10]=='优秀'){$key[$i][10]=95;}
        if($key[$i][10]=='良' || $key[$i][10]=='良好'){$key[$i][10]=85;}
        if($key[$i][10]=='中' || $key[$i][10]=='中等'){$key[$i][10]=75;}
        if($key[$i][10]=='及格'){$key[$i][10]=65;}
        if($key[$i][10]=='不及格'){$key[$i][10]=35;}
        if($key[$i][2]==$key[$i-1][2]){
            if($key[$i][10]>$key[$i-1][10]){$key[$i-1][10]=0;$key[$i-1][7]=0;}
            else{$key[$i][10]=0;$key[$i][7]=0;}
            // echo "同号取大";
        }
        // echo '课程号'.$key[$i][2].'学分'.$key[$i][7].'成绩'.$key[$i][10].'<br/>';
    }
    $sumfenshu=0;
    $sumxuefen=0;
    for($i=2;$i<=count($key)-1;$i++){
        //echo '课程号'.$key[$i][2].'学分'.$key[$i][7].'成绩'.$key[$i][10].'<br/>';
        $sumfenshu=$sumfenshu+$key[$i][10]*$key[$i][7];
        $sumxuefen=$sumxuefen+$key[$i][7];
    }
    $xuefenji=$sumfenshu/$sumxuefen;
    return $xuefenji;
}

//html表格转化为二维数组
function get_td_array($table) {
    $td_array='';
	//去除教室前的几位数字只剩下教室
    $table = preg_replace("(;\d\d\d)","",$table);
	$table = str_replace("th","td",$table);
	$table = str_replace("&lt;","",$table);
	$table = str_replace("&gt;","",$table);
	$table = str_replace("&nbsp;","",$table);
    $table = preg_replace("'<table[^>]*?>'si","",$table);
    $table = preg_replace("'<tr[^>]*?>'si","",$table);
    $table = preg_replace("'<td[^>]*?>'si","",$table);
    $table = str_replace("</tr>","{tr}",$table);
    $table = str_replace("</td>","{td}",$table);

    //去掉 HTML 标记
    $table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table);
    //去掉空白字符
    $table = preg_replace("'([rn])[s]+'","",$table);
    $table = str_replace(" ","",$table);
    $table = str_replace("\n","",$table);
    $table = str_replace(" ","",$table);
    $table = explode('{tr}', $table);
    array_pop($table); 
    foreach ($table as $key=>$tr) {
        $td = explode('{td}', $tr);
        array_pop($td);
        $td_array[] = $td;
    }
    return $td_array;
}
