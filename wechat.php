<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019/4/3
 * Time: 13:59
 */
header("Content-type: text/html; charset=utf-8");

include "config.php";
if(!verifyRequest() || !isset($_GET['code'])) {
    die("请求不合法!");
}

$code = $_GET['code'];
//获取access toekn 和 openid
$res =getJson("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code");
//print_r($res);
$access_token = $res["access_token"];
$openid = $res['openid'];

$userinfo = getJson("https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");

print_r($userinfo);//打印用户信息;

if($userinfo['nickname'] && $userinfo['headimgurl']) {


    $_SESSION['nickname'] = $userinfo['nickname'];

    #因为在前端用canvas 绘制图片,img src 直接引用 腾讯服务器上的头像地址 会出现跨域问题 我这里是将头像保存到 自己服务器上然后转成base64 给前端用
    $imgpath = 'head/' . $userinfo['openid'] . '_avatar.png';
    $img = curl_file_get_contents($userinfo['headimgurl'], $imgpath);//保存头像到指定位置
    $img = base64EncodeImage($imgpath);//转成base64;
    $_SESSION['headimgurl'] = $img; //保存到session

}else{
    $_SESSION['nickname'] = '';
    $_SESSION['headimgurl']='';
}
header("Location: index.php?state=".$_GET['state']);


/**
 * 验证请求来自 login 页面 -> 微信授权
 */
function verifyRequest(){
    session_start();
    if (isset($_GET['state']) && isset($_SESSION['wechatRand'])) {
        return $_SESSION['wechatRand'] == $_GET['state'] ? true : false;
    } else {
        return false;
    }
}

/**
 * @param $url
 * @return mixed
 * 使用 curl 发送请求
 */
function getJson($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output, true);
}

/**
 * @param $url
 * @param $path
 * @return mixed
 * 将网络图片 保存到服务器
 */
function curl_file_get_contents($url,$path)
{
    $hander = curl_init();
    $fp = fopen($path,'wb');
    curl_setopt($hander,CURLOPT_URL,$url);
    curl_setopt($hander,CURLOPT_FILE,$fp);
    curl_setopt($hander,CURLOPT_HEADER,0);
    curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($hander,CURLOPT_TIMEOUT,60);
    curl_exec($hander);
    curl_close($hander);
    fclose($fp);
    Return $path;
}

/**
 * @param $image_file
 * @return string
 * 将图片转为base64
 */
function base64EncodeImage ($image_file) {
    $base64_image = '';
    $image_info = getimagesize($image_file);
    $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
    $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
    return $base64_image;
}