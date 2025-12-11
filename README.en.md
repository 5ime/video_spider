# Video_Spider - Watermark-Free Short Video Download Tool
🌐 Available languages / 可用语言: English | [简体中文](README.zh.md)
> **Video_Spider** is an efficient and simple tool for downloading short videos without watermarks. Just provide a short video link to quickly get a watermark-free version. It supports multiple mainstream short video platforms, offering users a clean video download experience.

> [!NOTE]  
> The legacy version is in the master branch and is being slowly refactored...

✨ **Feel free to `Star` this project and submit feedback or suggestions via `Issues`.**

## 📋 Supported Platforms

This tool supports downloading watermark-free videos from multiple short video platforms. Please note that for some platforms, only watermarked versions can be downloaded where watermark removal isn’t supported.

> ✔️ Watermark-Free fully supported
> ⭕ Only supports watermarked versions

| Platform   | Status |  Platform   | Status | Platform   | Status |
| ---------- | ------ | ----------- | ------ | ---------- | ------ |
| **Douyin** | ✔️     | **Pipixia** | ✔️     | **Weibo**  | ⭕     |

## 🚀 Quick Start

1. **Clone the repository:**

   ```bash
   git clone https://github.com/5ime/video_spider.git
   ```

2. **Install dependencies:**
   Navigate into the project directory and install dependencies with Composer:

   ```bash
   cd video_spider
   composer install
   ```

3. **Start the service:**
   Run PHP’s built-in server, or configure and start Apache/Nginx as required:

   ```bash
   php -S localhost:8000 -t public
   ```

   If you are using Apache or Nginx, set up and start the web service according to your environment.

4. **Start using:**
   Open <http://localhost:8000> or your production URL to try out the watermark-free video download feature!

## 🛠️ How to Use

### 🔑 Request Parameters

- `url`: “Video link” → “Video URL”. Please ensure a valid and correct video URL is used.
  - For **GET** requests, you must `urlencode` the URL to avoid errors caused by special characters.

### 📡 Request Methods

This tool supports both **GET** and **POST** requests. Using **POST** is recommended for better stability.

### Example 1: POST request (recommended)

```bash
curl -X POST "http://localhost:8000" \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "url=<video_url>"
```

### Example 2: GET request

```bash
curl -G "http://localhost:8000" \
     --data-urlencode "url=<encoded_video_url>"
```

### 📤 Response Format

When the request succeeds, you’ll receive a JSON response with the following fields.
The `url` field is always present and contains the watermark-free video link.

| Field       | Description    | Field       | Description    | Field     | Description     | Field     | Description     |
| ----------- | -------------- | ----------- | -------------- | --------- | --------------- | --------- | --------------- |
| **author**  | Video author   | **avatar**  | Author avatar  | **like**  | No. of likes    | **time**  | Publish time    |
| **title**   | Video title    | **cover**   | Video cover    | **url**   | Video link      | **sex**   | Author gender   |
| **age**     | Author age     | **city**    | City           | **uid**   | Author ID       | **code**  | Status code     |

## ❓ FAQ

### 1. **How do I handle special characters in GET requests?**

When a video link includes characters like `#`, `&`, or `=`, a **GET** request may fail to pass the URL correctly. To prevent this:
- Use **POST** requests.
- If you must use **GET**, make sure the URL is properly `urlencode`d.

### 2. **Why does parsing fail for some platforms?**

Some platforms may not parse correctly because they require extra authentication or a `cookie`. To fix this:
- Update your `cookies` manually and save it in `config/cookies.php`;  
- If the problem persists, please open an **Issue** and we will handle it as soon as possible.

## 💡 Contributions Welcome

Got an idea or suggestion? Open an `Issue` and let us know. We’ll keep improving the project based on community feedback. (“PRs welcome”)

## ⚖️ Disclaimer

This project is for personal study and research only.  
If it infringes on the rights of any individual or organisation, please contact us and we’ll remove the relevant data promptly.

