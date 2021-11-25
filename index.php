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
if (strpos($url,'pipix')){
    $arr = $api->pipixia($url);
} elseif (strpos($url, 'douyin')){
    $arr = $api->douyin($url);
} elseif (strpos($url, 'huoshan')){
    $arr = $api->huoshan($url);
} elseif (strpos($url, 'h5.weishi')){
    $arr = $api->weishi($url);
} elseif (strpos($url, 'isee.weishi')){
    $arr = $api->weishi($id);
} elseif (strpos($url, 'weibo.com')){
    $arr = $api->weibo($url);
} elseif (strpos($url, 'oasis.weibo')){
    $arr = $api->lvzhou($url);
} elseif (strpos($url, 'zuiyou')){
    $arr = $api->zuiyou($url);
} elseif (strpos($url, 'xiaochuankeji')){
    $arr = $api->zuiyou($url);
    
} elseif (strpos($url, 'bbq.bilibili')){
    $arr = $api->bbq($url);
} elseif (strpos($url, 'kuaishou')){
    $arr = $api->kuaishou($url);
} elseif (strpos($url, 'quanmin')){
    if(empty($vid)){
        $arr = $api->quanmin($url);
    }else{
        $arr = $api->quanmin($vid);
    }
} elseif (strpos($url, 'moviebase')){
    $arr = $api->basai($basai_id);
} elseif (strpos($url, 'hanyuhl')){
    $arr = $api->before($url);
} elseif (strpos($url, 'eyepetizer')){
    $arr = $api->kaiyan($url);
} elseif (strpos($url, 'immomo')){
    $arr = $api->momo($url);
} elseif (strpos($url, 'vuevideo')){
    $arr = $api->vuevlog($url);
} elseif (strpos($url, 'xiaokaxiu')){
    $arr = $api->xiaokaxiu($url);
} elseif (strpos($url, 'ippzone') || strpos($url,'pipigx') ){
    $arr = $api->pipigaoxiao($url);
} elseif (strpos($url, 'qq.com')){
    $arr = $api->quanminkge($url);
} elseif (strpos($url, 'ixigua.com')){
    $arr = $api->xigua($url);
} elseif (strpos($url, 'doupai')){
    $arr = $api->doupai($url);
} elseif(strpos($url,'6.cn')){
    $arr = $api->sixroom($url);
} elseif(strpos($url,'huya.com/play/')){
    $arr = $api->huya($url);
} elseif(strpos($url,'pearvideo.com')){
    $arr = $api->pear($url);
} elseif(strpos($url,'xinpianchang.com')){
    $arr = $api->xinpianchang($url);
} elseif(strpos($url,'acfun.cn')){
    $arr = $api->acfan($url);
} elseif(strpos($url,'meipai.com')){
    $arr = $api->meipai($url);
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
