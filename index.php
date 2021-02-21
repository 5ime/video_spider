<?php 
header('Access-Control-Allow-Origin:*');
header('Content-type: application/json');
ini_set('display_errors','off');
error_reporting(E_ALL || ~E_NOTICE);
require 'src/video_spider.php';
$url = $_REQUEST['url'];
$id = $_REQUEST['id'];
$basai_id = $_REQUEST['data'];
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
    $arr = $api->quanmin($vid);
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
} elseif (strpos($url, 'ippzone') !== false){
    $arr = $api->pipigaoxiao($url);
} else {
    $arr = array(
        'code'  => 201,
        'msg' => '不支持您输入的链接'
    );
}
if (!empty($arr)){
    echo json_encode($arr, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
?>
