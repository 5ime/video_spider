# Video_Spider - 无水印短视频下载工具
🌐 可用语言 / Available languages: 简体中文 | [English](README.en.md)
> **Video_Spider** 是一款高效、简洁的短视频无水印下载工具。只需提供短视频链接，即可快速获取无水印的视频版本，支持多个主流短视频平台，为用户提供纯净的视频下载体验。

> [!NOTE]
> 旧版本在 [master](https://github.com/5ime/video_spider/tree/master) 分支中，新版正在缓慢重构...

✨ **欢迎为本项目添加 `Star`，并通过 `Issues` 提交反馈或建议。**

## 📋 支持平台

本工具支持从多个短视频平台下载无水印的视频。请注意，部分平台的短视频只能下载水印版本，无法去除水印。

> ✔️ 完全支持无水印  
> ⭕ 仅支持下载水印版本  

| 平台       | 状态 | 平台       | 状态 | 平台       | 状态 | 平台       | 状态 | 平台     | 状态 |
| ---------- | ---- | ---------- | ---- | ---------- | ---- | ---------- | ---- | -------- | ---- |
| **抖音**   | ✔️    | **皮皮虾** | ✔️    | **最右**   | ✔️    | **皮皮搞笑** | ✔️    | **微博** | ⭕    |

## 🚀 快速开始

1. **克隆仓库：**

   ```bash
   git clone https://github.com/5ime/video_spider.git
   ```

2. **安装依赖：**

   进入项目目录后，使用 Composer 安装项目依赖：

   ```bash
   cd video_spider
   composer install
   ```

3. **启动服务：**

   使用 PHP 内置服务器，或根据项目需求配置 Apache/Nginx：

   ```bash
   php -S localhost:8000 -t public
   ```

   如果您使用 Apache 或 Nginx，请根据项目的实际情况配置并启动 Web 服务。

4. **配置环境变量（可选）：**

   在项目根目录创建 `.env` 文件，配置 Cookie 和限流参数：

   ```env
   # Cookie 配置
   WEIBO_COOKIE='XSRF-TOKEN=xxx; SUB=xxx; SUBP=xxx; WBPSESS=xxx'

   # 速率限制配置（可选）
   RATE_LIMIT_ENABLED=true
   RATE_LIMIT_MAX_REQUESTS=60
   RATE_LIMIT_TIME_WINDOW=60

   # CURL 配置（可选）
   CURL_CONNECT_TIMEOUT=5
   CURL_TIMEOUT=10
   CURL_MAX_RETRIES=3
   ```

5. **开始使用：**

   访问 `http://localhost:8000` 或生产环境地址，即可体验无水印视频下载功能！

## 🛠️ 如何使用

### 🔑 请求参数

- `url`：视频链接。请确保提供有效且正确的视频 URL。
  - 对于 **GET** 请求，请务必对 URL 进行 `urlencode` 编码，避免特殊字符导致错误。

### 📡 请求方式

本工具支持 **GET** 和 **POST** 请求。建议使用 **POST** 请求，以保证更高的稳定性。

#### 示例 1：POST 请求（推荐）

```bash
curl -X POST "http://localhost:8000" \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "url=<video_url>"
```

#### 示例 2：GET 请求

```bash
curl -G "http://localhost:8000" \
     --data-urlencode "url=<encoded_video_url>"
```

### 📤 返回格式

请求成功后，您将获得一个包含以下字段的 JSON 响应。`url` 字段为必返字段，包含无水印的视频链接。

| 字段名     | 说明     | 字段名     | 说明     | 字段名   | 说明       | 字段名   | 说明         |
| ---------- | -------- | ---------- | -------- | -------- | ---------- | -------- | ------------ |
| **author** | 视频作者 | **avatar** | 作者头像 | **like** | 视频点赞量 | **time** | 视频发布时间 |
| **title**  | 视频标题 | **cover**  | 视频封面 | **url**  | 视频链接   | **sex**  | 作者性别     |
| **age**    | 作者年龄 | **city**   | 所在城市 | **uid**  | 作者ID     | **code** | 状态码       |

**错误码：**

- `400` - 参数错误（如：URL 格式无效、不支持的平台）
- `405` - 不支持的请求方法
- `422` - 参数验证失败（如：URL 参数为空）
- `429` - 请求过于频繁（触发速率限制）
- `500` - 服务器内部错误（如：解析失败）

#### 速率限制

默认限制为：**60 次请求 / 60 秒**（基于 IP 地址）

- 可通过环境变量 `RATE_LIMIT_MAX_REQUESTS` 和 `RATE_LIMIT_TIME_WINDOW` 调整
- 可通过环境变量 `RATE_LIMIT_ENABLED=false` 禁用速率限制

## ❓ 常见问题

### 1. **如何处理 GET 请求中的特殊字符问题？**

当短视频链接包含特殊字符（例如 `#`、`&`、`=` 等）时，GET 请求可能无法正确传递参数。为避免此问题，建议：
- 使用 **POST** 请求；
- 如果必须使用 **GET** 请求，请对 URL 进行正确的 `urlencode` 编码。

### 2. **为什么某些平台的视频解析失败？**

有些平台的短视频解析可能会失败，常见原因包括：
- **微博平台**：必须配置 `WEIBO_COOKIE`，否则无法解析（会提示"请先设置微博 cookie"）
- 视频链接无效或已过期
- 平台接口变更导致解析失败

解决方法：
- 确保已在 `.env` 文件中配置对应平台的 Cookie
- 验证视频链接是否有效
- 如果问题仍然存在，请提交 **Issue** 反馈，我们将尽快处理

## 💡 欢迎贡献

如果您有任何建议或想法，欢迎通过 `Issues` 提交反馈，我们将根据社区反馈不断改进项目。

## ⚖️ 免责声明

本项目仅供个人学习和研究使用。如果涉及到侵犯任何个人或团体的权益，请立即联系我们，我们将尽快处理相关问题并删除数据。