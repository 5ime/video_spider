<?php
/**
 * @package Video_spider
 * @author  iami233
 * @version 1.0.9
 * @link    https://github.com/5ime/Video_spider
 **/

namespace Video_spider;

class Video
{
    public function pipixia($url)
    {
        $loc = get_headers($url, true)['Location'];
        preg_match('/item\/(.*)\?/', $loc, $id);
        $arr       = json_decode(
            $this->curl('https://is.snssdk.com/bds/cell/detail/?cell_type=1&aid=1319&app_name=super&cell_id=' . $id[1]),
            true
        );
        $video_url = $arr['data']['data']['item']['origin_video_download']['url_list'][0]['url'];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'data' => [
                    'author' => $arr['data']['data']['item'] ['author']['name'],
                    'avatar' => $arr['data']['data']['item'] ['author']['avatar']['download_list'][0]['url'],
                    'time'   => $arr['data']['data']['display_time'],
                    'title'  => $arr['data']['data']['item']['content'],
                    'cover'  => $arr['data']['data']['item']['cover']['url_list'][0]['url'],
                    'url'    => $video_url
                ]
            ];
            return $arr;
        }
    }

    public function douyin($url)
    {
        $loc = get_headers($url, true)['Location'][1];
        preg_match('/video\/(.*)\?/', $loc, $id);
        $arr = json_decode(
            $this->curl('https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids=' . $id[1]),
            true
        );
        preg_match(
            '/href="(.*?)">Found/',
            $this->curl(
                str_replace('playwm', 'play', $arr['item_list'][0]["video"]["play_addr"]["url_list"][0])
            ),
            $matches
        );
        $video_url = str_replace('&', '&', $matches[1]);
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $arr['item_list'][0]['author']['nickname'],
                    'uid'    => $arr['item_list'][0]['author']['unique_id'],
                    'avatar' => $arr['item_list'][0]['author']['avatar_larger']['url_list'][0],
                    'like'   => $arr['item_list'][0]['statistics']['digg_count'],
                    'time'   => $arr['item_list'][0]["create_time"],
                    'title'  => $arr['item_list'][0]['share_info']['share_title'],
                    'cover'  => $arr['item_list'][0]['video']['origin_cover']['url_list'][0],
                    'url'    => $video_url,
                    'music'  => [
                        'author' => $arr['item_list'][0]['music']['author'],
                        'avatar' => $arr['item_list'][0]['music']['cover_large']['url_list'][0],
                        'url'    => $arr['item_list'][0]['music']['play_url']['url_list'][0],
                    ]
                ]
            ];
            return $arr;
        }
    }

    public function huoshan($url)
    {
        $loc = get_headers($url, true)['location'];
        preg_match('/item_id=(.*)&tag/', $loc, $id);
        $arr = json_decode($this->curl('https://share.huoshan.com/api/item/info?item_id=' . $id[1]), true);
        $url = $arr['data']['item_info']['url'];
        preg_match('/video_id=(.*)&line/', $url, $id);
        if (!empty($id)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'cover' => $arr["data"]["item_info"]["cover"],
                    'url'   => 'https://api-hl.huoshan.com/hotsoon/item/video/_playback/?video_id=' . $id[1]
                ]
            ];
            return $arr;
        }
    }

    public function weishi($url)
    {
        preg_match('/feed\/(.*)\b/', $url, $id);
        if (strpos($url, 'h5.weishi') != false) {
            $arr = json_decode(
                $this->curl('https://h5.weishi.qq.com/webapp/json/weishi/WSH5GetPlayPage?feedid=' . $id[1]),
                true
            );
        } else {
            $arr = json_decode(
                $this->curl('https://h5.weishi.qq.com/webapp/json/weishi/WSH5GetPlayPage?feedid=' . $url),
                true
            );
        }
        $video_url = $arr['data']['feeds'][0]['video_url'];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $arr['data']['feeds'][0]['poster']['nick'],
                    'avatar' => $arr['data']['feeds'][0]['poster']['avatar'],
                    'time'   => $arr['data']['feeds'][0]['poster']['createtime'],
                    'title'  => $arr['data']['feeds'][0]['feed_desc_withat'],
                    'cover'  => $arr['data']['feeds'][0]['images'][0]['url'],
                    'url'    => $video_url
                ]
            ];
            return $arr;
        }
    }

    public function weibo($url)
    {
        if (strpos($url, 'show?fid=') != false) {
            preg_match('/fid=(.*)/', $url, $id);
            $arr = json_decode($this->weibo_curl($id[1]), true);
        } else {
            preg_match('/\d+\:\d+/', $url, $id);
            $arr = json_decode($this->weibo_curl($id[0]), true);
        }
        if (!empty($arr)) {
            $one       = key($arr['data']['Component_Play_Playinfo']['urls']);
            $video_url = $arr['data']['Component_Play_Playinfo']['urls'][$one];
            $arr       = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $arr['data']['Component_Play_Playinfo']['author'],
                    'avatar' => $arr['data']['Component_Play_Playinfo']['avatar'],
                    'time'   => $arr['data']['Component_Play_Playinfo']['real_date'],
                    'title'  => $arr['data']['Component_Play_Playinfo']['title'],
                    'cover'  => $arr['data']['Component_Play_Playinfo']['cover_image'],
                    'url'    => $video_url
                ]
            ];
            return $arr;
        }
    }

    public function lvzhou($url)
    {
        $text = $this->curl($url);
        preg_match('/<div class=\"text\">(.*)<\/div>/', $text, $video_title);
        preg_match('/<div style=\"background-image:url\((.*)\)/', $text, $video_cover);
        preg_match('/<video src=\"([^\"]*)\"/', $text, $video_url);
        preg_match('/<div class=\"nickname\">(.*)<\/div>/', $text, $video_author);
        preg_match('/<a class=\"avatar\"><img src=\"(.*)\?/', $text, $video_author_img);
        preg_match('/<div class=\"like-count\">(.*)次点赞<\/div>/', $text, $video_like);
        $video_url = $video_url[1];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $video_author[1],
                    'avatar' => str_replace('1080.180', '1080.680', $video_author_img)[1],
                    'like'   => $video_like[1],
                    'title'  => $video_title[1],
                    'cover'  => $video_cover[1],
                    'url'    => $video_url,
                ]
            ];
            return $arr;
        }
    }

    public function zuiyou($url)
    {
        // 2021/7/7 感谢@yzh52521提供最新代码
        $text = $this->curl($url);
        preg_match('/"urlsrc":"(.*?)"/', $text, $video);
        preg_match('/:<\/span><h1>(.*?)<\/h1><\/div><\/div><div class=\"ImageBoxII\">/', $text, $video_title);
        preg_match('/<img alt=\"\" src=\"(.*?)\/id\/(.*?)\?w=540/', $text, $video_cover);
        $video_url = str_replace('\\', '/', str_replace('u002F', '', $video[1]));
        preg_match('/<span class=\"SharePostCard__name\">(.*?)<\/span>/', $text, $video_author);
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $video_author[1],
                    'title'  => $video_title[1],
                    'cover'  => 'https://file.izuiyou.com/img/png/id/' . $video_cover[2] . '/sz/600',
                    'url'    => $video_url,
                ]
            ];
            return $arr;
        }
    }

    public function bbq($url)
    {
        preg_match('/id=(.*)\b/', $url, $id);
        $arr       = json_decode($this->curl('https://bbq.bilibili.com/bbq/app-bbq/sv/detail?svid=' . $id[1]), true);
        $video_url = $arr['data']['play']['file_info'][0]['url'];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $arr['data']['user_info']['uname'],
                    'avatar' => $arr['data']['user_info']['face'],
                    'time'   => $arr['data']['pubtime'],
                    'like'   => $arr['data']['like'],
                    'title'  => $arr['data']['title'],
                    'cover'  => $arr['data']['cover_url'],
                    'url'    => $video_url,
                ]
            ];
            return $arr;
        }
    }

    public function kuaishou($url)
    {
        $loc  = get_headers($url, true)["Location"][0];
        $text = $this->curl($loc);
        preg_match('/{\"title\":\"(.*?)\",\"desc/', $text, $video_title);
        preg_match('/poster=\"(.*?)\"/', $text, $video_cover);
        preg_match('/srcNoMark\":\"(.*?)\"}/', $text, $video_url);
        preg_match('/<div class=\"auth-name\">(.*?)<\/div>/', $text, $video_author);
        preg_match('/<div class=\"auth-avatar\" style=\"background-image:url\((.*?)\)/', $text, $video_avatar);
        preg_match('/timestamp\":(.*?),\"/', $text, $video_time);
        $video_url = $video_url[1];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $video_author[1],
                    'avatar' => $video_avatar[1],
                    'time'   => $video_time[1],
                    "title"  => $video_title[1],
                    "cover"  => $video_cover[1],
                    "url"    => $video_url,
                ]
            ];
            return $arr;
        }
    }

    public function quanmin($id)
    {
        if (strpos($id, 'quanmin.baidu.com/v/')) {
            preg_match('/v\/(.*?)\?/', $id, $vid);
            $id = $vid[1];
        }
        $arr = json_decode(
            $this->curl(
                'https://quanmin.hao222.com/wise/growth/api/sv/immerse?source=share-h5&pd=qm_share_mvideo&vid=' . $id . '&_format=json'
            ),
            true
        );
        if (!empty($arr)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $arr["data"]["author"]['name'],
                    'avatar' => $arr["data"]["author"]["icon"],
                    "title"  => $arr["data"]["meta"]["title"],
                    "cover"  => $arr["data"]["meta"]["image"],
                    "url"    => $arr["data"]["meta"]["video_info"]["clarityUrl"][0]['url']
                ]
            ];
            return $arr;
        }
    }

    public function basai($id)
    {
        $arr       = json_decode(
            $this->curl('http://www.moviebase.cn/uread/api/m/video/' . $id . '?actionkey=300303'),
            true
        );
        $video_url = $arr[0]['data']['videoUrl'];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'time'  => $arr[0]['data']['createDate'],
                    'title' => $arr[0]['data']['title'],
                    "cover" => $arr[0]['data']['coverUrl'],
                    "url"   => $video_url
                ]
            ];
            return $arr;
        }
    }

    public function before($url)
    {
        preg_match('/detail\/(.*)\?/', $url, $id);
        $arr       = json_decode($this->curl('https://hlg.xiatou.com/h5/feed/detail?id=' . $id[1]), true);
        $video_url = $arr['data'][0]['mediaInfoList'][0]['videoInfo']['url'];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $arr['data'][0]['author']['nickName'],
                    'avatar' => $arr['data'][0]['author']['avatar']['url'],
                    'like'   => $arr['data'][0]['diggCount'],
                    'time'   => $arr['recTimeStamp'],
                    'title'  => $arr['data'][0]['title'],
                    "cover"  => $arr['data'][0]['staticCover'][0]['url'],
                    "url"    => $video_url
                ]
            ];
            return $arr;
        }
    }

    public function kaiyan($url)
    {
        preg_match('/\?vid=(.*)\b/', $url, $id);
        $arr       = json_decode($this->curl('https://baobab.kaiyanapp.com/api/v1/video/' . $id[1] . '?f=web'), true);
        $video     = 'https://baobab.kaiyanapp.com/api/v1/playUrl?vid=' . $id[1] . '&resourceType=video&editionType=default&source=aliyun&playUrlType=url_oss&ptl=true';
        $video_url = get_headers($video, true)["Location"];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'title' => $arr['title'],
                    "cover" => $arr['coverForFeed'],
                    "url"   => $video_url
                ]
            ];
            return $arr;
        }
    }

    public function momo($url)
    {
        preg_match('/new-share-v2\/(.*)\.html/', $url, $id);
        if (count($id) < 1) {
            //获取PC端时的feedid
            preg_match('/momentids=(\w+)/', $url, $id);
        }
        $post_data = ["feedids" => $id[1],];
        $arr       = json_decode(
            $this->post_curl('https://m.immomo.com/inc/microvideo/share/profiles', $post_data),
            true
        );
        $video_url = $arr['data']['list'][0]['video']['video_url'];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $arr['data']['list'][0]['user']['name'],
                    'avatar' => $arr['data']['list'][0]['user']['img'],
                    'uid'    => $arr['data']['list'][0]['user']['momoid'],
                    'sex'    => $arr['data']['list'][0]['user']['sex'],
                    'age'    => $arr['data']['list'][0]['user']['age'],
                    'city'   => $arr['data']['list'][0]['video']['city'],
                    'like'   => $arr['data']['list'][0]['video']['like_cnt'],
                    'title'  => $arr['data']['list'][0]['content'],
                    "cover"  => $arr['data']['list'][0]['video']['cover']['l'],
                    "url"    => $video_url
                ]
            ];
            return $arr;
        }
    }

    public function vuevlog($url)
    {
        $text = $this->curl($url);
        preg_match('/<title>(.*?)<\/title>/', $text, $video_title);
        preg_match('/<meta name=\"twitter:image\" content=\"(.*?)\">/', $text, $video_cover);
        preg_match('/<meta property=\"og:video:url\" content=\"(.*?)\">/', $text, $video_url);
        preg_match('/<div class=\"infoItem name\">(.*?)<\/div>/', $text, $video_author);
        preg_match('/<div class="avatarContainer"><img src="(.*?)\"/', $text, $video_avatar);
        preg_match('/<div class=\"likeTitle\">(.*) friends/', $text, $video_like);
        $video_url = $video_url[1];
        if (!empty($video_url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $video_author[1],
                    'avatar' => $video_avatar[1],
                    'like'   => $video_like[1],
                    'title'  => $video_title[1],
                    "cover"  => $video_cover[1],
                    "url"    => $video_url,
                ]
            ];
            return $arr;
        }
    }

    public function xiaokaxiu($url)
    {
        preg_match('/id=(.*)\b/', $url, $id);
        $sign = md5('S14OnTD#Qvdv3L=3vm&time=' . time());
        $arr  = json_decode(
            $this->curl(
                'https://appapi.xiaokaxiu.com/api/v1/web/share/video/' . $id[1] . '?time=' . time(),
                ["x-sign : $sign"]
            ),
            true
        );
        if ($arr['code'] != -2002) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $arr['data']['video']['user']['nickname'],
                    'avatar' => $arr['data']['video']['user']['avatar'],
                    'like'   => $arr['data']['video']['likedCount'],
                    'time'   => $arr['data']['video']['createdAt'],
                    'title'  => $arr['data']['video']['title'],
                    'cover'  => $arr['data']['video']['cover'],
                    'url'    => $arr['data']['video']['url'][0]
                ]
            ];
            return $arr;
        }
    }

    public function pipigaoxiao($url)
    {
        preg_match('/post\/(.*)/', $url, $id);
        $arr = json_decode($this->pipigaoxiao_curl($id[1]), true);
        $id  = $arr["data"]["post"]["imgs"][0]["id"];
        if (!empty($id)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'title' => $arr["data"]["post"]["content"],
                    'cover' => 'https://file.ippzone.com/img/view/id/' . $arr["data"]["post"]["imgs"][0]["id"],
                    'url'   => $arr["data"]["post"]["videos"]["$id"]["url"]
                ]
            ];
            return $arr;
        }
    }

    public function quanminkge($url)
    {
        preg_match('/\?s=(.*)/', $url, $id);
        $text = $this->curl('https://kg.qq.com/node/play?s=' . $id[1]);
        preg_match('/<title>(.*?)-(.*?)-/', $text, $video_title);
        preg_match('/cover\":\"(.*?)\"/', $text, $video_cover);
        preg_match('/playurl_video\":\"(.*?)\"/', $text, $video_url);
        preg_match('/{\"activity_id\":0\,\"avatar\":\"(.*?)\"/', $text, $video_avatar);
        preg_match('/<p class=\"singer_more__time\">(.*?)<\/p>/', $text, $video_time);
        $video_url = $video_url[1];
        if (!empty($video_url)) {
            $arr = [
                'code'   => 200,
                'msg'    => '解析成功',
                'author' => $video_title[1],
                'avatar' => $video_avatar[1],
                'time'   => $video_time[1],
                'data'   => [
                    'title' => $video_title[2],
                    'cover' => $video_cover[1],
                    'url'   => $video_url,
                ]
            ];
            return $arr;
        }
    }

    public function xigua($url)
    {
        if (strpos($url, 'v.ixigua.com') != false) {
            $loc = get_headers($url, true)['location'];
            preg_match('/video\/(.*)\//', $loc, $id);
            $url = 'https://www.ixigua.com/' . $id[1];
        }
        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36 ",
            "cookie:MONITOR_WEB_ID=7892c49b-296e-4499-8704-e47c1b150c18; ixigua-a-s=1; ttcid=af99669b6304453480454f150701d5c226; BD_REF=1; __ac_nonce=060d88ff000a75e8d17eb; __ac_signature=_02B4Z6wo00f01kX9ZpgAAIDAKIBBQUIPYT5F2WIAAPG2ad; ttwid=1%7CcIsVF_3vqSIk4XErhPB0H2VaTxT0tdsTMRbMjrJOPN8%7C1624806049%7C08ce7dd6f7d20506a41ba0a331ef96a6505d96731e6ad9f6c8c709f53f227ab1"
        ];
        $text    = $this->curl($url, $headers);
        preg_match('/<script id=\"SSR_HYDRATED_DATA\">window._SSR_HYDRATED_DATA=(.*?)<\/script>/', $text, $jsondata);
        $data   = json_decode(str_replace('undefined', 'null', $jsondata[1]), 1);
        $result = $data["anyVideo"]["gidInformation"]["packerData"]["video"];
        $video  = $result["videoResource"]["dash"]["dynamic_video"]["dynamic_video_list"][2]["main_url"];
        preg_match('/(.*?)=&vr=/', base64_decode($video), $video_url);
        $music = $result["videoResource"]["dash"]["dynamic_video"]["dynamic_audio_list"][0]["main_url"];
        preg_match('/(.*?)=&vr=/', base64_decode($music), $music_url);
        $video_author = $result['user_info']['name'];
        $video_avatar = str_replace('300x300.image', '300x300.jpg', $result['user_info']['avatar_url']);
        $video_cover  = $data["anyVideo"]["gidInformation"]["packerData"]["pSeries"]["firstVideo"]["middle_image"]["url"];
        $video_title  = $result["title"];
        if (!empty($url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $video_author,
                    'avatar' => $video_avatar,
                    'like'   => $result['video_like_count'],
                    'time'   => $result['video_publish_time'],
                    'title'  => $video_title,
                    'cover'  => $video_cover,
                    'url'    => $video_url[0],
                    'music'  => [
                        'url' => $music_url[0]
                    ]
                ]
            ];
            return $arr;
        }
    }

    public function doupai($url)
    {
        preg_match("/topic\/(.*?).html/", $url, $d_url);
        $vid      = $d_url[1];
        $base_url = "https://v2.doupai.cc/topic/" . $vid . ".json";
        $data     = json_decode($this->curl($base_url), true);
        $url      = $data["data"]["videoUrl"];
        $title    = $data["data"]["name"];
        $cover    = $data["data"]["imageUrl"];
        $time     = $data['data']['createdAt'];
        $author   = $data['data']['userId'];
        if (!empty($url)) {
            $arr = [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    "title"  => $title,
                    "cover"  => $cover,
                    'time'   => $time,
                    'author' => $author['name'],
                    'avatar' => $author['avatar'],
                    "url"    => $url
                ]
            ];
            return $arr;
        }
    }

    /**
     * 6间房
     */
    public function sixroom($url)
    {
        preg_match(
            "/http[s]?:\/\/(?:[a-zA-Z]|[0-9]|[$-_@.&+]|[!*\(\),]|(?:%[0-9a-fA-F][0-9a-fA-F]))+/",
            $url,
            $deal_url
        );
        $headers = [
            'user-agent'       => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.125 Safari/537.36',
            'x-requested-with' => 'XMLHttpRequest'
        ];
        $rows    = $this->curl($deal_url[0], $headers);
        preg_match('/tid: \'(\w+)\',/', $rows, $tid);
        $base_url = 'https://v.6.cn/message/message_home_get_one.php';
        $content  = $this->curl($base_url . '?tid=' . $tid[1], $headers);
        $content  = json_decode($content, 1);
        return [
            'code' => 200,
            'msg'  => '解析成功',
            'data' => [
                'title'  => $content["content"]["content"][0]["content"]['title'],
                'cover'  => $content["content"]["content"][0]["content"]['url'],
                'video'  => $content["content"]["content"][0]["content"]['playurl'],
                'author' => $content["content"]["content"][0]['alias'],
                'avatar' => $content["content"]["content"][0]['userpic'],
            ]
        ];
    }

    //虎牙
    public function huya($url)
    {
        preg_match('/\/(\d+).html/', $url, $vid);
        $api      = 'https://liveapi.huya.com/moment/getMomentContent';
        $response = $this->curl(
            $api . '?videoId=' . $vid[1],
            [
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36',
                'referer'    => 'https://v.huya.com/',
            ]
        );
        $content  = json_decode($response, 1);
        if ($content['status'] === 200) {
            $url       = $content["data"]["moment"]["videoInfo"]["definitions"][0]["url"];
            $cover     = $content["data"]["moment"]["videoInfo"]["videoCover"];
            $title     = $content["data"]["moment"]["videoInfo"]["videoTitle"];
            $avatarUrl = $content["data"]["moment"]["videoInfo"]["avatarUrl"];
            $author    = $content["data"]["moment"]["videoInfo"]["nickName"];
            $time      = $content["data"]["moment"]["cTime"];
            $like      = $content["data"]["moment"]["favorCount"];
            return [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'title'     => $title,
                    'cover'     => $cover,
                    'video_url' => $url,
                    'time'      => $time,
                    'like'      => $like,
                    'author'    => $author,
                    'avatar'    => $avatarUrl
                ]
            ];
        }
    }

    /**
     * 梨视频
     * @param $url
     * @return array
     */
    public function pear($url)
    {
        $html = $this->curl($url);
        preg_match('/<h1 class=\"video-tt\">(.*?)<\/h1>/', $html, $title);
        preg_match('/_(\d+)/', $url, $feed_id);
        $base_url = sprintf("https://www.pearvideo.com/videoStatus.jsp?contId=%s&mrd=%s", $feed_id[1], time());
        $response = $this->pear_curl($base_url, $url);
        $content  = json_decode($response, 1);
        if ($content['resultCode'] == 1) {
            $video     = $content["videoInfo"]["videos"]["srcUrl"];
            $cover     = $content["videoInfo"]["video_image"];
            $timer     = $content["systemTime"];
            $video_url = str_replace($timer, "cont-" . $feed_id[1], $video);
            return [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'title' => $title[1],
                    'cover' => $cover,
                    'url'   => $video_url,
                    'time'  => $timer,
                ]
            ];
        }
    }

    /**
     * 新片场
     * @param $url
     * @return array
     */
    public function xinpianchang($url)
    {
        $api_headers  = [
            "User-Agent"   => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.125 Safari/537.36",
            "referer"      => $url,
            "origin"       => "https://www.xinpianchang.com",
            "content-type" => "application/json"
        ];
        $home_headers = [
            "User-Agent"                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.125 Safari/537.36",
            "upgrade-insecure-requests" => "1"
        ];
        $html         = $this->curl($url, $home_headers);
        preg_match('/var modeServerAppKey = "(.*?)";/', $html, $key);
        preg_match('/var vid = "(.*?)";/', $html, $vid);
        $base_url = sprintf(
            "https://mod-api.xinpianchang.com/mod/api/v2/media/%s?appKey=%s&extend=%s",
            $vid[1],
            $key[1],
            "userInfo,userStatus"
        );
        $response = $this->xinpianchang_curl($base_url, $api_headers, $url);
        $content  = json_decode($response, 1);
        if ($content['status'] == 0) {
            $cover  = $content['data']["cover"];
            $title  = $content['data']["title"];
            $videos = $content['data']["resource"]["progressive"];
            $author = $content['data']['owner']['username'];
            $avatar = $content['data']['owner']['avatar'];
            $video  = [];
            foreach ($videos as $v) {
                $video[] = ['profile' => $v['profile'], 'url' => $v['url']];
            }
            return [
                'code' => 200,
                'msg'  => '解析成功',
                'data' => [
                    'author' => $author,
                    'avatar' => $avatar,
                    'cover'  => $cover,
                    'title'  => $title,
                    'url'    => $video
                ]
            ];
        }
        return [
            'code' => 200,
            'msg'  => '解析失败'
        ];
    }

    public function acfan($url)
    {
        $headers = [
            'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4'
        ];
        $html    = $this->acfun_curl($url, $headers);
        preg_match('/var videoInfo =\s(.*?);/', $html, $info);
        $videoInfo = json_decode(trim($info[1]), 1);
        preg_match('/var playInfo =\s(.*?);/', $html, $play);
        $playInfo = json_decode(trim($play[1]), 1);
        return [
            'code' => 200,
            'msg'  => '解析成功',
            'data' => [
                'title' => $videoInfo['title'],
                'cover' => $videoInfo['cover'],
                'time'  => $videoInfo['time'],
                'url'   => $playInfo['streams'][0]['playUrls'][0],
            ]
        ];
    }


    private function acfun_curl($url, $headers = [])
    {
        $header = ['User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1'];
        $con    = curl_init((string)$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        if (!empty($headers)) {
            curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
        } else {
            curl_setopt($con, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($con, CURLOPT_FOLLOWLOCATION,1);

        curl_setopt($con, CURLOPT_TIMEOUT, 5000);

        return curl_exec($con);
    }
    private function curl($url, $headers = [])
    {
        $header = ['User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1'];
        $con    = curl_init((string)$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        if (!empty($headers)) {
            curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
        } else {
            curl_setopt($con, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($con, CURLOPT_TIMEOUT, 5000);
        $result = curl_exec($con);
        return $result;
    }

    private function post_curl($url, $post_data)
    {
        $postdata = http_build_query($post_data);
        $options  = [
            'http' => [
                'method'  => 'POST',
                'content' => $postdata,
            ]
        ];
        $context  = stream_context_create($options);
        $result   = @file_get_contents($url, false, $context);
        return $result;
    }

    private function pipigaoxiao_curl($id)
    {
        $post_data = "{\"pid\":" . $id . ",\"type\":\"post\",\"mid\":null}";
        $ch        = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://share.ippzone.com/ppapi/share/fetch_content");
        curl_setopt($ch, CURLOPT_REFERER, "http://share.ippzone.com/ppapi/share/fetch_content");
        curl_setopt(
            $ch,
            CURLOPT_USERAGENT,
            "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36"
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    private function weibo_curl($id)
    {
        $cookie    = "login_sid_t=6b652c77c1a4bc50cb9d06b24923210d; cross_origin_proto=SSL; WBStorage=2ceabba76d81138d|undefined; _s_tentry=passport.weibo.com; Apache=7330066378690.048.1625663522444; SINAGLOBAL=7330066378690.048.1625663522444; ULV=1625663522450:1:1:1:7330066378690.048.1625663522444:; TC-V-WEIBO-G0=35846f552801987f8c1e8f7cec0e2230; SUB=_2AkMXuScYf8NxqwJRmf8RzmnhaoxwzwDEieKh5dbDJRMxHRl-yT9jqhALtRB6PDkJ9w8OaqJAbsgjdEWtIcilcZxHG7rw; SUBP=0033WrSXqPxfM72-Ws9jqgMF55529P9D9W5Qx3Mf.RCfFAKC3smW0px0; XSRF-TOKEN=JQSK02Ijtm4Fri-YIRu0-vNj";
        $post_data = "data={\"Component_Play_Playinfo\":{\"oid\":\"$id\"}}";
        $ch        = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://weibo.com/tv/api/component?page=/tv/show/" . $id);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);//设置Cookie
        curl_setopt($ch, CURLOPT_REFERER, "https://weibo.com/tv/show/" . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    private function pear_curl($url, $referer)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt(
            $ch,
            CURLOPT_USERAGENT,
            "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36"
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    private function xinpianchang_curl($url, $headers, $referer)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
