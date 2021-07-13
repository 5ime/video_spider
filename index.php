<?php
header('Access-Control-Allow-Origin:*');
header('Content-type: application/json');
ini_set('display_errors','off');
error_reporting(E_ALL || ~E_NOTICE);
require 'src/video_spider.php';
$url = $_REQUEST['url'];
$id = $_GET['id'];
$vid = $_GET['vid'];
$basai_id = $_GET['data'];
use Video_spider\Video;
$api = new Video;
if (strpos($url,'pipix') !== false){
    $arr = $api->pipixia($url);
} elseif (strpos($url, 'douyin') !== false){
    $arr = $api->douyin($url);
} elseif (strpos($url, 'huoshan') !== false){
    $arr = $api->huoshan($url);
} elseif (strpos($url, 'h5.weishi') !== false){
    $arr = $api->weishi($url);
} elseif (strpos($url, 'isee.weishi') !== false){
    $arr = $api->weishi($id);
} elseif (strpos($url, 'weibo.com') !== false){
    $arr = $api->weibo($url);
} elseif (strpos($url, 'oasis.weibo') !== false){
    $arr = $api->lvzhou($url);
} elseif (strpos($url, 'zuiyou') !== false){
    $arr = $api->zuiyou($url);
} elseif (strpos($url, 'bbq.bilibili') !== false){
    $arr = $api->bbq($url);
} elseif (strpos($url, 'kuaishou') !== false){
    $arr = $api->kuaishou($url);
} elseif (strpos($url, 'quanmin') !== false){
    if(empty($vid)){
        $arr = $api->quanmin($url);
    }else{
        $arr = $api->quanmin($vid);
    }
} elseif (strpos($url, 'moviebase') !== false){
    $arr = $api->basai($basai_id);
} elseif (strpos($url, 'hanyuhl') !== false){
    $arr = $api->before($url);
} elseif (strpos($url, 'eyepetizer') !== false){
    $arr = $api->kaiyan($url);
} elseif (strpos($url, 'immomo') !== false){
    $arr = $api->momo($url);
} elseif (strpos($url, 'vuevideo') !== false){
    $arr = $api->vuevlog($url);
} elseif (strpos($url, 'xiaokaxiu') !== false){
    $arr = $api->xiaokaxiu($url);
} elseif (strpos($url, 'ippzone') !== false || strpos($url,'pipigx') !== false ){
    $arr = $api->pipigaoxiao($url);
} elseif (strpos($url, 'qq.com') !== false){
    $arr = $api->quanminkge($url);
} elseif (strpos($url, 'ixigua.com') !== false){
    $arr = $api->xigua($url);
} elseif (strpos($url, 'doupai') !== false){
    $arr = $api->doupai($url);
} elseif(strpos($url,'6.cn')!==false){
    $arr = $api->sixroom($url);
} elseif(strpos($url,'huya.com')!==false){
    $arr = $api->huya($url);
} elseif(strpos($url,'pearvideo.com')!==false){
    $arr = $api->pear($url);
} elseif(strpos($url,'xinpianchang.com')!==false){
    $arr = $api->xinpianchang($url);
} elseif(strpos($url,'acfun.cn')!==false){
    $arr = $api->acfan($url);
} else {
    $arr = array(
        'code'  => 201,
        'msg' => '不支持您输入的链接'
    );
}
if (!empty($arr)){
    echo json_encode($arr, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
else{
    $arr = array(
        'code' => 201,
        'msg' => '解析失败',
    );
    echo json_encode($arr, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
?>
