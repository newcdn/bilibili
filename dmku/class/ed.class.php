<?php
$_config = require_once('../config.inc.php');
global $_config;
$link = @new mysqli($_config['数据库']['地址'], $_config['数据库']['用户名'], $_config['数据库']['密码'], $_config['数据库']['名称']) or die("提示：数据库连接失败！");
//$conn -> set_charset('utf8');

$cid = $_POST['cid'];
$id = $_POST['id'];
$text = $_POST['text'];
$videotime = $_POST['videotime'];
$ip = $_POST['ip'];
$time = $_POST['time'];
// 更新数据
mysql_query("UPDATE danmaku_report SET text='$text' WHERE cid=$cid", $link) or die('修改数据出错：' . mysql_error());
mysql_query("UPDATE danmaku_list SET text='$text' WHERE cid=$cid", $link) or die('修改数据出错：' . mysql_error());
