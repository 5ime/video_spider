![video_spider](https://socialify.git.ci/5ime/video_spider/image?description=1&descriptionEditable=%E6%94%AF%E6%8C%8123%E4%B8%AA%E7%9F%AD%E8%A7%86%E9%A2%91%E5%B9%B3%E5%8F%B0%E5%8E%BB%E6%B0%B4%E5%8D%B0%E4%B8%8B%E8%BD%BD&font=Inter&forks=1&language=1&owner=1&pattern=Circuit%20Board&stargazers=1&theme=Light)

目前支持23个平台视频去水印下载，欢迎各位Star，**提交issues时请附带视频链接**。

## 支持平台

| 平台 | 状态| 平台 | 状态| 平台 | 状态| 平台 | 状态| 平台 | 状态|
|  ----  | ----  | ----  | ---- |----|----|----|----|----|----|
| 皮皮虾 | ✔ | 抖音短视频 | ✔ | 火山短视频 | ✔| 皮皮搞笑 | ✔ | 全民K歌 | ✔ |
| 微视短视频 | ✔ | 微博 | ✔ | 最右 | ✔| vuevlog | ✔ |小咖秀| ✔|
| 轻视频 | ✔ | 快手短视频 | ✔ | 全民小视频 | ✔|陌陌 | ✔ | Before避风 | ✔ |
| 西瓜视频 | ✔|逗拍|✔|虎牙|✔|6间房|✔|梨视频|✔|
| 新片场 | ✔|Acfun|✔|美拍|✔|||||

## 请求示例

支持GET/POST `url`参数必填，请优先使用 `POST` 请求，`GET` 请求自行 `urlencode` 编码

## 返回数据

因为平台众多，所以返回的参数不固定，但 `title`, `cover`, `url` 一定会有

| 字段名 | 说明 | 字段名 | 说明 |字段名 | 说明 |字段名 | 说明 |
|  ----  | ----  | ----  | ---- |---- |---- |----|----|
| author | 视频作者| avatar | 作者头像 | like | 视频点赞量 | time | 视频发布时间 |
| title | 视频标题 | cover | 视频封面 | url | 视频无水印链接 | sex  | 作者性别 |
| age | 作者年龄 | city | 所在城市 | uid | 作者id | code | 状态码 |


## 调用示例

如果你不会调用 我在`demo`目录下放两个最基本的调用演示

- `demo.html`第`98`行请修改为`你的接口地址`
- `demo.py`第`7`行请修改为`你的接口地址`

## FAQ

**为什么演示网址界面和`demo`文件夹里的不一样**

因为我用vue重写了(https://github.com/5ime/vue-page)

**网址中包含特殊字符导致GET请求无法传递正确的参数值**

传递的参数中包含`#&=`之类的，可能无法正确传递参数值，建议使用`POST请求`或`urlencode编码`后进行GET请求

**关于有些视频平台解析失败**

有些平台需要cookie，请手动更新cookie，如果还是解析失败，请提交issues

**短视频图集图片去水印**

https://github.com/5ime/images_spider

**抖音X-Bogus校验**

目前使用的 https://github.com/B1gM8c/X-Bogus 提供的服务

你也可以基于我的模板 https://github.com/5ime/Tiktok_Signature 一键部署到 vercel，需要修改的地方如下

```php
$url = 'https://tiktok.iculture.cc/X-Bogus';
$data = json_encode(array('url' => 'https://www.douyin.com/aweme/v1/web/aweme/detail/?aweme_id=' . $id[0] . '&aid=1128&version_name=23.5.0&device_platform=android&os_version=2333','userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'));
$header = array('Content-Type: application/json');
$url = json_decode($this->curl($url, $data, $header), true)['param'];
// 改为
$url = '你的 vercel 地址';
$data = json_encode(array('url' => 'https://www.douyin.com/aweme/v1/web/aweme/detail/?aweme_id=' . $id[0] . '&aid=1128&version_name=23.5.0&device_platform=android&os_version=2333','userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'));
$header = array('Content-Type: application/json');
$url = json_decode($this->curl($url, $data, $header), true)['data']['url'];
```

## 免责声明

本仓库只为学习研究，如涉及侵犯个人或者团体利益，请与我取得联系，我将主动删除一切相关资料，谢谢！
