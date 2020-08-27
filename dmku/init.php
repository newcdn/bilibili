<?php
date_default_timezone_set('Asia/Shanghai');

$_config = require_once('config.inc.php');
if (!$_config['安装']) {
    header("Location: install/");
    die();
}
header("Access-Control-Allow-Origin: " . AllowOrigin($_config['允许url']));
header("Content-Type:application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {   //预检请求
    //header("Access-Control-Allow-Credentials: true");  暂时不会用到
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");  //允许的请求方法
    header("Access-Control-Allow-Headers: content-type");   //允许携带的首部字段
}

if ($_config['数据库']['类型'] === 'mysql') {
    if ($_config['数据库']['方式'] === 'pdo') {
        require_once('class/pdo.class.php');
        new sql('mysql');
    }

    if ($_config['数据库']['方式'] === 'mysqli') {
        require_once('class/mysqli.class.php');
        new sql;
    }
}

if ($_config['数据库']['类型'] === 'sqlite') {

    $_config['数据库']['地址'] = __DIR__ . '/db/' . $_config['数据库']['地址'];
    if ($_config['数据库']['方式'] === 'pdo') {
        require_once('class/pdo.class.php');
        new sql('sqlite');
    }

    if ($_config['数据库']['方式'] === 'sqlite3') {
        require_once('class/sqlite3.class.php');
        new sql;
    }
}



function AllowOrigin($domains = [])
{
    $domain = null;
    if (empty($_SERVER['HTTP_ORIGIN'])) return '*';
    if (empty($domains)) return '*';

    foreach ($domains as $v) {
        if ($v == $_SERVER['HTTP_ORIGIN']) {
            $domain = $v;
            break;
        }
    }
    return $domain;
}


function showmessage($code = 23, $mes = null)
{
    global $_config;
    if ($_GET['ac'] == "report") {
        $id = 'report';
        $length = 0;
        $code = 6666;
    } elseif ($_GET['ac'] == "get") {
        $length = count($mes);
    } elseif ($_GET['ac'] == "so") {
        $length = count($mes);
        $json = [
            'code' => $code,
            'count' => $length,
            'data' => $mes
        ];
        die(json_encode($json));
        exit;
    } elseif ($_GET['ac'] == "list") {
        $conn = @new mysqli($_config['数据库']['地址'], $_config['数据库']['用户名'], $_config['数据库']['密码'], $_config['数据库']['名称']);
        $conn->set_charset('utf8');
        $sql = "select count(*) from danmaku_list ORDER BY time DESC";
        $res = $conn->query($sql);
        $length = $res->fetch_row();
        $length = $length[0];
        $json = [
            'code' => $code,
            'count' => $length,
            'data' => $mes
        ];
        die(json_encode($json));
        exit;
    } elseif ($_GET['ac'] == "reportlist") {
        $conn = @new mysqli($_config['数据库']['地址'], $_config['数据库']['用户名'], $_config['数据库']['密码'], $_config['数据库']['名称']);
        $conn->set_charset('utf8');
        $sql = "select count(*) from danmaku_report ORDER BY time DESC";
        $res = $conn->query($sql);
        $length = $res->fetch_row();
        $length = $length[0];
        $json = [
            'code' => $code,
            'count' => $length,
            'data' => $mes
        ];
        die(json_encode($json));
        exit;
    } elseif ($_GET['ac'] == "dm") {
        $length = count($mes);
        if ($length == 0) {
            $mov = "一条弹幕都没有，赶紧来一发吧！";
        } else {
            $mov = "有 $length 条弹幕列队来袭~做好准备吧！";
        }
        $tips = [2, "right", "#fff", "", "$mov"];
        $tips1 = [$_config['tips']['time'], "top", $_config['tips']['color'], "", $_config['tips']['text']];
        array_unshift($mes, $tips, $tips1);
    }
    $id = $_GET['id'];
    $json = [
        'code' => $code,
        'name' => $id,
        'danum' => $length,
        'danmuku' => $mes
    ];
    die(json_encode($json));
}
function  succeedmsg($code = 23, $mes = null)
{
    $json = [
        'code' => $code,
        'danmuku' => $mes
    ];
    die(json_encode($json));
}

function get_ip()
{
    global $_config;
    if ($_config['is_cdn']) {
        if (preg_match('/,/', $_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        } else {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
