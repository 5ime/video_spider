# Video_spider
目前支持15个平台视频去水印下载，欢迎各位Star，提交issues前请先查看[支持的链接](https://github.com/5ime/video_spider#%E9%93%BE%E6%8E%A5%E6%A0%BC%E5%BC%8F)

## 支持平台

| 平台 | 状态| 平台 | 状态| 平台 | 状态| 平台 | 状态| 平台 | 状态|
|  ----  | ----  | ----  | ---- |----|----|----|----|----|----|
| 皮皮虾 | ✔ | 抖音短视频 | ✔ | 火山短视频 | ✔| 皮皮搞笑 | ✔ | 全民K歌 | ✔ |
| 微视短视频 | ✔ | 微博 | ✔ | 最右 | ✔| vuevlog | ✔ |小咖秀| ✔|
| 轻视频 | ✔ | 快手短视频 | ✔ | 全民小视频 | ✔|陌陌 | ✔ | Before避风 | ✔ | 开眼 | ✔|

## 请求示例

支持GET/POST `url`参数必填

GET测试(随时关闭)：http://xn--v6qw21h0gd43u.xn--ses554g/?url=https://v.douyin.com/m2mun2

POST自行测试

## 返回数据

| 字段名 | 说明 | 字段名 | 说明 |字段名 | 说明 |字段名 | 说明 |
|  ----  | ----  | ----  | ---- |---- |---- |----|----|
| author | 视频作者| avatar | 作者头像 | like | 视频点赞量 | time | 视频发布时间 |
| title | 视频标题 | cover | 视频封面 | url | 视频无水印链接 | sex  | 作者性别 |
| age | 作者年龄 | city | 所在城市 | uid | 作者id | code | 状态码 |


## 调用示例

如果你不会调用 我在`demo`目录下放两个最基本的调用演示

- `demo.html`第`98`行请修改为`你的接口地址`
- `demo.py`第`7`行请修改为`你的接口地址`


## 链接格式

<details>
<summary>点我展开</summary>

```text
皮皮虾：https://h5.pipix.com/s/JrQ5yNH/
抖音：http://v.douyin.com/5w5JwL/
火山：https://share.huoshan.com/hotsoon/s/CpNjM1bqNa8/
微视：https://h5.weishi.qq.com/weishi/feed/76EaWNkEF1IqtfYVH/
     https://isee.weishi.qq.com/ws/app-pages/share/index.html?wxplay=1&id=71sGFcjJ51LczPOQB&collectionid=ai-602fb09fbf6f04f1626a4abc&spid=1579870022402553&qua=v1_and_weishi_8.10.0_588_312027000_d&chid=100081003&pkg=&attach=cp_reserves3_1000370721
微博：https://weibo.com/tv/show/1034:4607135049515082?mid=46456489789
     https://video.weibo.com/show?fid=1034:4605703432896565
绿洲：https://m.oasis.weibo.cn/v1/h5/share?sid=4497689997350015&luicode=10001122&lfid=lz_qqfx&bid=4497689997350015
最右：https://share.izuiyou.com/hybrid/share/post?pid=196279131&zy_to=applink&share_count=1&m=0372f49e6e3c576a56498dc65e626d8f&d=eda64ae931b41c1
轻视频：https://bbq.bilibili.com/video/?id=1580113023042844866
快手：https://v.kuaishou.com/9e55Md
全民小视频：https://quanmin.hao222.com/sv2?source=share-h5&pd=qm_share_mvideo&vid=3092829461307269694&shareTime=1613994266&shareid=2666196829&shared_cuid=0a23aguSHtlqa2uPg8v_ig882i_VPHumgPSR8gOH-8K9LUKgB&shared_uid=AUKgB
巴塞：http://m.moviebase.cn/?actionkey=video_view&data=378de374fb57416b94345e01318872fe
避风：https://m.hanyuhl.com/detail/50947038?shareId=638033751
开眼：https://www.eyepetizer.net/detail.html?vid=209323&utm_campaign=routine&utm_medium=share&utm_source=qq&uid=0&resourceType=video&udid=9923d62e13154466831a2955bd897c9aecdcc083&vc=6030071&vn=6.3.7&size=1080X2034&deviceModel=vivo%20X20A&first_channel=vivo&last_channel=vivo&system_version_code=27
陌陌：https://m.immomo.com/s/moment/new-share-v2/at8975483503.html?time=1598040846&name=TPhAEIKjUKckxettBzhM0w==&avatar=842F9EFD-711F-6D93-3568-E221FEE485D220200822&isdaren=0&isuploader=0&from=qqfriend
Vuevlog：https://v.vuevideo.net/share/post/2586974035524877860
小咖秀：https://mobile.xiaokaxiu.com/video?id=84123438
皮皮搞笑：http://h5.ippzone.com/pp/post/78266943052
全民k歌：https://kg3.qq.com/node/user/bb132c338e/song/play-edLkcwAsRj?s=bCyoDlbCUhcjXbkQ&shareuid=&topsource=znxvljkwehoit_rqojkwehfguioqef_fnajkgfb&g_f=
```

</details>

## 更新日志

- 2021/2/21 新增微视 isee前缀域名，新增皮皮搞笑 修复微博匹配失败
- 2021/2/23 修复全民匹配失败，修复避风匹配失败，新增全民K歌
- 2021/3/26 新增视频解析失败返回`201`参数

## 免责声明

本仓库只为学习研究，如涉及侵犯个人或者团体利益，请与我取得联系，我将主动删除一切相关资料，谢谢！
