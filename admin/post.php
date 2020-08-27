<?php
if (isset($_GET['act']) && $_GET['act'] == 'reset' ) {

    $file = 'yzm.config.php'; //旧目录
    $newFile = 'bak/config.php'; //新目录
    copy($newFile, $file); //拷贝到新目录
    //unlink($file); //删除旧目录下的文件
    exit;
}
if (isset($_GET['act']) && $_GET['act'] == 'setting' && isset($_POST['edit']) && $_POST['edit'] == 1 ) {
    $datas = $_POST;
    $data = $datas['yzm'];

    if (file_put_contents('data.php', "<?php\n \$yzm =  ".var_export($data, true).";\n?>")) {
        echo "{code:1,msg:保存成功}";
    } else {
        echo "<script>alert('修改失败！可能是文件权限问题，请给予yzm.config.php写入权限！');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
    }
    $yzm = $data;
}
