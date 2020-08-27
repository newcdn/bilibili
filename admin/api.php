<?php

/*if (isset($_SERVER['HTTP_REFERER'])) {
    if (stripos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
        echo '{"code":110,"msg":"看什么看，再看我报警了。。"}';
        exit;
    }
} else {
      echo '{"code":110,"msg":"看什么看，再看我报警了。。"}';
      exit;
}*/
include ('data.php');
    $json = [
       'code' => 1,
       'data' => $yzm
    ];
die(json_encode($json));


//$config = file_get_contents('config.json'); 

//echo $config;
