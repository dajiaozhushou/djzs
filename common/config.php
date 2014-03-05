<?php
/**
 * Created by Mr.wang
 * Date: 13-12-1
 * Time: 下午5:44
 */
 // 数据库配置
function connect(){
      $link=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
      mysql_select_db(SAE_MYSQL_DB);
      mysql_query('set names utf8');
}