<?php
/**
 * @package Video_spider
 * @author  iami233
 * @version 1.0.7
 * @link    https://github.com/5ime/Video_spider
**/

namespace Video_spider;
class Video
{    
    public function pipixia($url){
        $loc = get_headers($url, true)['Location'];
        preg_match('/item\/(.*)\?/',$loc,$id);
        $arr = json_decode($this->curl('https://is.snssdk.com/bds/cell/detail/?cell_type=1&aid=1319&app_name=super&cell_id='.$id[1]), true);
        $video_url = $arr['data']['data']['item']['origin_video_download']['url_list'][0]['url'];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'data' => array(
                    'author' => $arr['data']['data']['item'] ['author']['name'],
                    'avatar' => $arr['data']['data']['item'] ['author']['avatar']['download_list'][0]['url'],
                    'time' => $arr['data']['data']['display_time'],
                    'title' => $arr['data']['data']['item']['content'],
                    'cover' => $arr['data']['data']['item']['cover']['url_list'][0]['url'],
                    'url' => $video_url
                )
            );
            return $arr;
        }
    }

    public function douyin($url){
        $loc = get_headers($url, true)['Location'][1];
        preg_match('/video\/(.*)\?/',$loc,$id);
        $arr = json_decode($this->curl('https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids='.$id[1]), true);
        preg_match('/href="(.*?)">Found/', $this->curl(str_replace('playwm', 'play', $arr['item_list'][0]["video"]["play_addr"]["url_list"][0])), $matches);
        $video_url = str_replace('&', '&', $matches[1]);
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $arr['item_list'][0]['author']['nickname'],
                    'uid' => $arr['item_list'][0]['author']['unique_id'],
                    'avatar' => $arr['item_list'][0]['author']['avatar_larger']['url_list'][0],
                    'like' => $arr['item_list'][0]['statistics']['digg_count'],
                    'time' => $arr['item_list'][0]["create_time"],
                    'title' => $arr['item_list'][0]['share_info']['share_title'],
                    'cover' => $arr['item_list'][0]['video']['origin_cover']['url_list'][0],
                    'url' => $video_url,
                    'music' => array(
                        'author' => $arr['item_list'][0]['music']['author'],
                        'avatar' => $arr['item_list'][0]['music']['cover_large']['url_list'][0],
                        'url' => $arr['item_list'][0]['music']['play_url']['url_list'][0],
                    )
                )
            );
            return $arr;
        }
    }

    public function huoshan($url){
        $loc = get_headers($url, true)['location'];
        preg_match('/item_id=(.*)&tag/',$loc,$id);
        $arr = json_decode($this->curl('https://share.huoshan.com/api/item/info?item_id='.$id[1]), true);
        $url = $arr['data']['item_info']['url'];
        preg_match('/video_id=(.*)&line/',$url,$id);
        if (!empty($id)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'cover' => $arr["data"]["item_info"]["cover"],
                    'url' => 'https://api-hl.huoshan.com/hotsoon/item/video/_playback/?video_id='.$id[1]
                )
            );
            return $arr;
        }
    }

    public function weishi($url){
        preg_match('/feed\/(.*)\b/',$url,$id);
        if (strpos($url,'h5.weishi') != false){
            $arr = json_decode($this->curl('https://h5.weishi.qq.com/webapp/json/weishi/WSH5GetPlayPage?feedid='.$id[1]),true);
        } else {
            $arr = json_decode($this->curl('https://h5.weishi.qq.com/webapp/json/weishi/WSH5GetPlayPage?feedid='.$url),true);   
        }
        $video_url = $arr['data']['feeds'][0]['video_url'];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $arr['data']['feeds'][0]['poster']['nick'],
                    'avatar' => $arr['data']['feeds'][0]['poster']['avatar'],
                    'time' =>$arr['data']['feeds'][0]['poster']['createtime'],
                    'title' => $arr['data']['feeds'][0]['feed_desc_withat'],
                    'cover' => $arr['data']['feeds'][0]['images'][0]['url'],
                    'url' => $video_url
                )
            );
            return $arr;
        }
    }

    public function weibo($url){
        if (strpos($url,'show?fid=') != false){
            preg_match('/fid=(.*)/',$url,$id);
            $arr = json_decode($this->curl('https://video.h5.weibo.cn/s/video/object?object_id='.$id[1]),true);
        } else {
            preg_match('/\d+\:\d+/',$url,$id);
            $arr = json_decode($this->curl('https://video.h5.weibo.cn/s/video/object?object_id='.$id[0]),true);
        }
        $video_url = $arr['data']['object']['stream']['hd_url'];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $arr['data']['object']['author']['screen_name'],
                    'avatar' => $arr['data']['object']['author']['profile_image_url'],
                    'time' => $arr['data']['object']['created_at'],
                    'title' => $arr['data']['object']['summary'],
                    'cover' => $arr['data']['object']['image']['url'],
                    'url' => $video_url
                )
            );
            return $arr;
        }
    }

    public function lvzhou($url){
        $text = $this->curl($url);
        preg_match('/<div class=\"text\">(.*)<\/div>/',$text,$video_title);
        preg_match('/<div style=\"background-image:url\((.*)\)/',$text,$video_cover);
        preg_match('/<video src=\"([^\"]*)\"/',$text,$video_url);
        preg_match('/<div class=\"nickname\">(.*)<\/div>/',$text,$video_author);
        preg_match('/<a class=\"avatar\"><img src=\"(.*)\?/',$text,$video_author_img);
        preg_match('/<div class=\"like-count\">(.*)次点赞<\/div>/',$text,$video_like);                  
        $video_url = $video_url[1];
        if (!empty($video_url)){                  
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $video_author[1],
                    'avatar' => str_replace('1080.180','1080.680',$video_author_img)[1],
                    'like' => $video_like[1],
                    'title' => $video_title[1],
                    'cover' => $video_cover[1],
                    'url' => $video_url,
                )
            );
            return $arr;
        }
    }

    public function zuiyou($url){
        // 2021/7/7 感谢@yzh52521提供最新代码
        $text = $this->curl($url);
        preg_match('/"urlsrc":"(.*?)"/', $text, $video);
        preg_match('/:<\/span><h1>(.*?)<\/h1><\/div><\/div><div class=\"ImageBoxII\">/', $text, $video_title);
        preg_match('/<img alt=\"\" src=\"(.*?)\/id\/(.*?)\?w=540/', $text, $video_cover);
        $video_url = str_replace('\\', '/', str_replace('u002F', '', $video[1]));
        preg_match('/<span class=\"SharePostCard__name\">(.*?)<\/span>/', $text, $video_author);
        if (!empty($video_url)){ 
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $video_author[1],
                    'title' => $video_title[1],
                    'cover' => 'https://file.izuiyou.com/img/png/id/' . $video_cover[2].'/sz/600',
                    'url' => $video_url,
                )
            );
            return $arr;
        }    
    }

    public function bbq($url){
        preg_match('/id=(.*)\b/',$url,$id);
        $arr = json_decode($this->curl('https://bbq.bilibili.com/bbq/app-bbq/sv/detail?svid='.$id[1]),true);
        $video_url = $arr['data']['play']['file_info'][0]['url'];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $arr['data']['user_info']['uname'],
                    'avatar' => $arr['data']['user_info']['face'],
                    'time' => $arr['data']['pubtime'],
                    'like' => $arr['data']['like'],
                    'title' => $arr['data']['title'],
                    'cover' => $arr['data']['cover_url'],
                    'url' => $video_url,
                )
            );
            return $arr; 
        } 
    }

    public function kuaishou($url){
        $loc = get_headers($url, true)["Location"][0];
        $text = $this->curl($loc);
        preg_match('/{\"title\":\"(.*?)\",\"desc/', $text, $video_title);
        preg_match('/poster=\"(.*?)\"/', $text, $video_cover);
        preg_match('/srcNoMark\":\"(.*?)\"}/', $text, $video_url);
        preg_match('/<div class=\"auth-name\">(.*?)<\/div>/', $text, $video_author);
        preg_match('/<div class=\"auth-avatar\" style=\"background-image:url\((.*?)\)/', $text, $video_avatar);
        preg_match('/timestamp\":(.*?),\"/', $text, $video_time);
        $video_url = $video_url[1];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $video_author[1],
                    'avatar' => $video_avatar[1],
                    'time' => $video_time[1],
                    "title"=> $video_title[1],
                    "cover"=> $video_cover[1],
                    "url"=> $video_url,
                )
            );
            return $arr;
        }
    }

    public function quanmin($id){
        $arr = json_decode($this->curl('https://quanmin.hao222.com/wise/growth/api/sv/immerse?source=share-h5&pd=qm_share_mvideo&vid='.$id.'&_format=json'),true);
        if (!empty($arr)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $arr["data"]["author"]['name'],
                    'avatar' => $arr["data"]["author"]["icon"],
                    "title"=> $arr["data"]["meta"]["title"],
                    "cover"=> $arr["data"]["meta"]["image"],
                    "url"=> $arr["data"]["meta"]["video_info"]["clarityUrl"][0]['url']
                )
            );
            return $arr;
        }
    }
    public function basai($id){
        $arr = json_decode($this->curl('http://www.moviebase.cn/uread/api/m/video/'.$id.'?actionkey=300303'),true);
        $video_url = $arr[0]['data']['videoUrl'];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'time' => $arr[0]['data']['createDate'],
                    'title' => $arr[0]['data']['title'],
                    "cover"=> $arr[0]['data']['coverUrl'],
                    "url"=> $video_url
                )
            );
            return $arr;
        }
    }
    
    public function before($url){
        preg_match('/detail\/(.*)\?/',$url,$id);
        $arr = json_decode($this->curl('https://hlg.xiatou.com/h5/feed/detail?id='.$id[1]),true);
        $video_url = $arr['data'][0]['mediaInfoList'][0]['videoInfo']['url'];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $arr['data'][0]['author']['nickName'],
                    'avatar' => $arr['data'][0]['author']['avatar']['url'],
                    'like' => $arr['data'][0]['diggCount'],
                    'time' => $arr['recTimeStamp'],
                    'title' => $arr['data'][0]['title'],
                    "cover"=> $arr['data'][0]['staticCover'][0]['url'],
                    "url"=> $video_url
                )
            );
            return $arr;
        }
    }

    public function kaiyan($url){
        preg_match('/\?vid=(.*)\b/',$url,$id);
        $arr = json_decode($this->curl('https://baobab.kaiyanapp.com/api/v1/video/'.$id[1].'?f=web'),true);
        $video = 'https://baobab.kaiyanapp.com/api/v1/playUrl?vid='.$id[1].'&resourceType=video&editionType=default&source=aliyun&playUrlType=url_oss&ptl=true';
        $video_url = get_headers($video, true)["Location"];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'title' => $arr['title'],
                    "cover"=> $arr['coverForFeed'],
                    "url"=> $video_url
                )
            );
            return $arr;
        }
    }

    public function momo($url){
        preg_match('/new-share-v2\/(.*)\.html/',$url,$id);
        $post_data = array("feedids" => $id[1],);
        $arr = json_decode($this->post_curl('https://m.immomo.com/inc/microvideo/share/profiles', $post_data),true);
        $video_url = $arr['data']['list'][0]['video']['video_url'];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $arr['data']['list'][0]['user']['name'],
                    'avatar' => $arr['data']['list'][0]['user']['img'],
                    'uid' => $arr['data']['list'][0]['user']['momoid'],
                    'sex' =>$arr['data']['list'][0]['user']['sex'],
                    'age' => $arr['data']['list'][0]['user']['age'],
                    'city' => $arr['data']['list'][0]['video']['city'],
                    'like' => $arr['data']['list'][0]['video']['like_cnt'],
                    'title' => $arr['data']['list'][0]['content'],
                    "cover"=> $arr['data']['list'][0]['video']['cover']['l'],
                    "url"=> $video_url
                )
            );
            return $arr;
        }
    }

    public function vuevlog($url){
        $text = $this->curl($url);
        preg_match('/<title>(.*?)<\/title>/', $text, $video_title);
        preg_match('/<meta name=\"twitter:image\" content=\"(.*?)\">/', $text, $video_cover);
        preg_match('/<meta property=\"og:video:url\" content=\"(.*?)\">/', $text, $video_url);
        preg_match('/<div class=\"infoItem name\">(.*?)<\/div>/', $text, $video_author);
        preg_match('/<div class="avatarContainer"><img src="(.*?)\"/', $text, $video_avatar);
        preg_match('/<div class=\"likeTitle\">(.*) friends/', $text, $video_like);
        $video_url = $video_url[1];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $video_author[1],
                    'avatar' => $video_avatar[1],
                    'like' => $video_like[1],
                    'title' => $video_title[1],
                    "cover" => $video_cover[1],
                    "url" => $video_url, 
                )
            );
            return $arr;
        }
    }

    public function xiaokaxiu($url){
        preg_match('/id=(.*)\b/',$url,$id);
        $sign = md5('S14OnTD#Qvdv3L=3vm&time='.time());
        $arr = json_decode($this->curl('https://appapi.xiaokaxiu.com/api/v1/web/share/video/'.$id[1].'?time='.time(), ["x-sign : $sign"]),true);
        if ($arr['code'] != -2002 ){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $arr['data']['video']['user']['nickname'],
                    'avatar' => $arr['data']['video']['user']['avatar'],
                    'like' => $arr['data']['video']['likedCount'],
                    'time' => $arr['data']['video']['createdAt'],
                    'title' => $arr['data']['video']['title'],
                    'cover' => $arr['data']['video']['cover'],
                    'url' => $arr['data']['video']['url'][0]
                )
            );
            return $arr;
        }
    }
    
    public function pipigaoxiao($url){
        preg_match('/post\/(.*)/', $url, $id);
        $arr = json_decode($this->pipigaoxiao_curl($id[1]), true);
        $id = $arr["data"]["post"]["imgs"][0]["id"];
        if (!empty($id)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'title' => $arr["data"]["post"]["content"],
                    'cover' => 'https://file.ippzone.com/img/view/id/'.$arr["data"]["post"]["imgs"][0]["id"],
                    'url' => $arr["data"]["post"]["videos"]["$id"]["url"]
                )
            );
            return $arr;
        }
    }

    public function quanminkge($url){
        preg_match('/\?s=(.*)/',$url,$id);
        $text = $this->curl('https://kg.qq.com/node/play?s='.$id[1]);
        preg_match('/<title>(.*?)-(.*?)-/', $text, $video_title);
        preg_match('/cover\":\"(.*?)\"/', $text, $video_cover);
        preg_match('/playurl_video\":\"(.*?)\"/', $text, $video_url);
        preg_match('/{\"activity_id\":0\,\"avatar\":\"(.*?)\"/', $text, $video_avatar);
        preg_match('/<p class=\"singer_more__time\">(.*?)<\/p>/', $text, $video_time);
        $video_url = $video_url[1];
        if (!empty($video_url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'author' => $video_title[1],
                'avatar' => $video_avatar[1],
                'time' => $video_time[1],
                'data' => array(
                    'title' => $video_title[2],
                    'cover' => $video_cover[1],
                    'url' => $video_url,
                )
            );
            return $arr;
        }
    }
    
    public function xigua($url){
        // 2021/7/7 感谢@yzh52521提供最新代码
        if (strpos($url,'v.ixigua.com') != false){
            $loc = get_headers($url, true)['location'];
            preg_match('/video\/(.*)\//',$loc,$id);
            $url = 'https://www.ixigua.com/'.$id[1];
        }
        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36 ",
            "cookie:MONITOR_WEB_ID=7892c49b-296e-4499-8704-e47c1b150c18; ixigua-a-s=1; ttcid=af99669b6304453480454f150701d5c226; BD_REF=1; __ac_nonce=060d88ff000a75e8d17eb; __ac_signature=_02B4Z6wo00f01kX9ZpgAAIDAKIBBQUIPYT5F2WIAAPG2ad; ttwid=1%7CcIsVF_3vqSIk4XErhPB0H2VaTxT0tdsTMRbMjrJOPN8%7C1624806049%7C08ce7dd6f7d20506a41ba0a331ef96a6505d96731e6ad9f6c8c709f53f227ab1"
        ];
        $text = $this->curl($url,$headers);
        preg_match('/<script id=\"SSR_HYDRATED_DATA\">window._SSR_HYDRATED_DATA=(.*?)<\/script>/', $text, $jsondata);
        $data = json_decode(str_replace('undefined', 'null', $jsondata[1]), 1);
        $result = $data["anyVideo"]["gidInformation"]["packerData"]["video"];
        $video = $result["videoResource"]["dash"]["dynamic_video"]["dynamic_video_list"];
        $base_video_url = $video[3]['main_url'] . $video[3]['backup_url_1'];
        preg_match('/(.*?)=&vr=/', base64_decode($base_video_url), $video_url);
        $music = $result["videoResource"]["dash"]["dynamic_video"]["dynamic_audio_list"];
        $base_music_url = $music[0]['main_url'] . $music[0]['backup_url_1'];
        $music_url = base64_decode($base_music_url);
        $video_author = $result['user_info']['name'];
        $video_avatar = str_replace('300x300.image','300x300.jpg',$result['user_info']['avatar_url']);
        $video_cover = $data["anyVideo"]["gidInformation"]["packerData"]["pSeries"]["firstVideo"]["middle_image"]["url"];
        $video_title = $result["title"];
        if (!empty($url)){
            $arr = array(
                'code' => 200,
                'msg' => '解析成功',
                'data' => array(
                    'author' => $video_author,
                    'avatar' => $video_avatar,
                    'like' => $result['video_like_count'],
                    'time' => $result['video_publish_time'],
                    'title' => $video_title,
                    'cover' => $video_cover,
                    'url' => $video_url[0],
                    'music' => array(
                        'url' => $music_url
                    )
                )
            );
            return $arr;
        }
    }

    private function curl($url,$headers=[])
    {
        $header = array( 'User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1');
        $con = curl_init((string)$url);
        curl_setopt($con,CURLOPT_HEADER,False);
        curl_setopt($con,CURLOPT_SSL_VERIFYPEER,False);
        curl_setopt($con,CURLOPT_RETURNTRANSFER,true);
        if (!empty($headers)) {
            curl_setopt($con,CURLOPT_HTTPHEADER,$headers);
        } else {
            curl_setopt($con,CURLOPT_HTTPHEADER,$header);
        }
        curl_setopt($con,CURLOPT_TIMEOUT,5000);
        $result = curl_exec($con);
        return $result;
    }

    private function post_curl($url, $post_data)
    {
        $postdata = http_build_query($post_data);
        $options = array('http' => array(
            'method' => 'POST',
            'content' => $postdata,
        ));
        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        return $result;
    }

    private function pipigaoxiao_curl($id)
    {
        $post_data = "{\"pid\":" . $id . ",\"type\":\"post\",\"mid\":null}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://share.ippzone.com/ppapi/share/fetch_content");
        curl_setopt($ch, CURLOPT_REFERER, "http://share.ippzone.com/ppapi/share/fetch_content");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
