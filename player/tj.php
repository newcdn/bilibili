<?php

//首先你要有读写文件的权限
//本程序可以直接运行,第一次报错

$online_log = "count.dat"; //保存人数的文件,
$timeout = 30; //30秒内没动作者,认为掉线
$entries = file($online_log);
$temp = array();
for ($i = 0; $i < count($entries); $i++) {

    $entry = explode(",", trim($entries[$i]));

    if (($entry[0] != getenv('REMOTE_ADDR')) && ($entry[1] > time())) {

        array_push($temp, $entry[0] . "," . $entry[1] . "\n"); //取出其他浏览者的信息,并去掉超时者,保存进$temp

    }
}
array_push($temp, getenv('REMOTE_ADDR') . "," . (time() + ($timeout)) . "\n"); //更新浏览者的时间
$users_online = count($temp); //计算在线人数
$entries = implode("", $temp);

//写入文件
$fp = fopen($online_log, "w");
flock($fp, LOCK_EX); //flock() 不能在NFS以及其他的一些网络文件系统中正常工作
fputs($fp, $entries);
flock($fp, LOCK_UN);
fclose($fp);
echo "$users_online";
