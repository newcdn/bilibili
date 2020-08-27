<?php
//ini_set("display_errors", "On");
//error_reporting(E_ALL);

//https://api.bilibili.com/x/v2/dm/history?type=1&date=xxxx-xx-xx&oid=xxxxx  历史弹幕接口，需要cookies
//include ('../admin/config.php');
header("Content-Type:application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
/*if ($yzm['ok']==1) {

if (isset($_SERVER['HTTP_REFERER'])) {
    if (stripos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
        echo '{"code":110,"msg":"看什么看，再看我报警了。。"}';
        exit;
    }
} else {
      echo '{"code":110,"msg":"看什么看，再看我报警了。。"}';
      exit;
}
}*/

$cid = $_GET['cid'] ?: 0;
$av = $_GET['av'] ?: 0;
$p = $_GET['p'] ?: 1;
preg_match("/^[0-9]+$/", $av) ?: $av = 0;
preg_match("/^[0-9]+$/", $p) ?: $p = 1;
preg_match("/^[0-9]+$/", $cid) ?: $cid = 0;

if ($cid > 0 or ($cid > 0 and $av > 0)) {
    $xml = curl_get('https://api.bilibili.com/x/v1/dm/list.so?oid=' . $cid);
    echo xml_json($xml);
} elseif ($av > 0 and $cid <= 0) {
    $cid = get_cid($av, $p);
    $xml = curl_get('https://api.bilibili.com/x/v1/dm/list.so?oid=' . $cid);
    echo xml_json($xml);
} else {
    echo '{"code":1,"mes":"参数错误"}';
}



function curl_get($url, $gzip = 1)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    if ($gzip) curl_setopt($curl, CURLOPT_ENCODING, "gzip");
    $content = curl_exec($curl);
    curl_close($curl);
    return $content;
}
function hex2rgb($hexColor)
{
    $color = str_replace('#', '', $hexColor);
    if (strlen($color) > 3) {
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));

        $rgb = 'rgb(' . $r . ',' . $g . ',' . $b . ')';
    } else {
        $color = $hexColor;
        $r = substr($color, 0, 1) . substr($color, 0, 1);
        $g = substr($color, 1, 1) . substr($color, 1, 1);
        $b = substr($color, 2, 1) . substr($color, 2, 1);
        $rgb = array(
            'r' => hexdec($r),
            'g' => hexdec($g),
            'b' => hexdec($b)
        );
    }
    return $rgb;
}

function xml_json($xml)
{
    $xml = simplexml_load_string($xml); //将文件转换成 对象
    $xmljson = json_encode($xml);
    $danmuku = json_decode($xmljson, true);
    foreach ($danmuku['d'] as $k => $v) {
        foreach ($xml->d[$k]->attributes() as $_v) {
            $danmuku['d'][$k] = [$v];
            $danmuku['d'][$k][] = explode(",", (string) $_v);
        }
    }
    $danmuku = $danmuku['d'];
    $json = ['code' => 23];
    foreach ($danmuku as $k => $v) {
        // 请不要随意调换下列数组赋值顺序
        $json['danmuku'][$k][] = (float) $v[1][0];  //弹幕出现时间(s)
        //弹幕样式

        if ($v[1][1] <= 3) { //滚动
            $json['danmuku'][$k][] = 'right';
        }

        if ($v[1][1] == 5) { //顶端
            $json['danmuku'][$k][] = 'top';
        }

        if ($v[1][1] == 4) { //底端
            $json['danmuku'][$k][] = 'bottom';
        }

        $json['danmuku'][$k][] = hex2rgb($v[1][6]); //字体的颜色

        $json['danmuku'][$k][] = "";  //bilibili 弹幕发送者的ID
        $json['danmuku'][$k][] = $v[0];  //弹幕文本
        //$json['total'] = $k + 1;  //就当它不存在吧，弹幕总数
    }

    return json_encode($json);
}

function get_cid($av, $p)
{
    $data = json_decode(curl_get('https://api.bilibili.com/x/player/pagelist?aid=' . $av), true);
    if (empty($data['data'][$p - 1]['cid'])) {
        die('{"code":1,"mes":"参数错误"}');
    }
    return $data['data'][$p - 1]['cid'];
}
