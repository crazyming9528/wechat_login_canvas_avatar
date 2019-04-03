<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019/4/3
 * Time: 13:39
 */
header("Content-type: text/html; charset=utf-8");

include "config.php";



if (filter_has_var(INPUT_GET,"login")){
    session_start();
    $rand = rand(10000,99999);
    $_SESSION['wechatRand'] = $rand;
    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=".$rand."#wechat_redirect";
    header("location: ".$url);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <title>登录</title>
</head>
<body>
<div class="container">
    <h5 class="text-center">微信登录 获取并使永canvas绘制用户头像</h5>
    <div class="row ">
        <div class="col-12  text-center">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="get">
                <button type="submit" name="login" class="btn btn-success">微信登录</button>
            </form></div>
    </div>
</div>
</body>
</html>