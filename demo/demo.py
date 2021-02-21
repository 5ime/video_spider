import json
import requests

def ppx_url():
    url = input('请输入视频链接:')
    # https://h5.pipix.com/s/hukXsy/
    data = requests.get('这里填写你的接口地址例如：https://domain.com/video/?url=' + url)
    video_title = data.json()['data']['title']
    video_cover = data.json()['data']['cover']
    video_url =  data.json()['data']['url']
    print('视频标题：%s\n视频封面：%s\n无水印地址：%s'%(video_title,video_cover,video_url))
ppx_url()