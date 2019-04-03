<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019/4/3
 * Time: 13:39
 */
header("Content-type: text/html; charset=utf-8");
include "config.php";

if (!verifyRequest()) {
    header("location: login.php");
    exit();
}

$nickname=$_SESSION['nickname'];
$headimgurl = $_SESSION['headimgurl'];

/**
 * 验证请求来自 login 页面 -> 微信授权
 */
function verifyRequest()
{
    echo $_GET['n'];
    echo $_SESSION['wechatRand'];
    session_start();
    if (isset($_GET['state']) && isset($_SESSION['wechatRand']) && isset($_SESSION['nickname']) && isset($_SESSION['headimgurl']) ) {
        return $_SESSION['wechatRand'] == $_GET['state'] ? true : false;
    } else {
        return false;
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>hello</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        .wrap {
            width: 100vw;
            height: 100vh;
            /*background-image: url("./bg/bg.jpg");*/
            /*background-size: cover;*/
            position: relative;

        }
        /*h2{*/
            /*position: absolute;*/
            /*top:50%;*/
            /*left: 50%;*/
            /*transform: translate(-50%,-50%);*/
        /*}*/
    </style>
</head>
<body>
<div class="wrap">
<!--    <h2>hello--><?php //echo  $nickname?><!--</h2>-->
    <canvas id="canvans" ></canvas>
    <img id="bg" src="./bg/bg.jpg" style="display: none"  alt="">
    <img id="avatar" src="<?php echo $headimgurl?>"  style="display: none" alt="">
</div>
<script>
var dom = document.getElementById("canvans");
var bg = document.getElementById("bg");
var  avatar = document.getElementById("avatar");

dom.width = document.documentElement.clientWidth;
dom.height = document.documentElement.clientHeight;

bg.onload=function () {
    var ctx = dom.getContext('2d');
    ctx.drawImage(bg,0,0);
    ctx.drawImage(avatar,0,0,100,100);
    ctx.font = "30px Helvetica, Arial, sans-serif";
    ctx.fillText("hello<?php echo $nickname?>", 65, 127);
    dom.toDataURL();//转base64 如果需要的话 可以建一个img标签 src 使用它
};

</script>
</body>
</html>



