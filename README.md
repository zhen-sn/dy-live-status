# 抖音直播检测工具

基于Laravel框架开发的抖音主播直播状态监控工具，支持实时检测主播开播状态并通过短信通知用户。

## 功能特性

- 用户注册登录系统
- 添加/删除/管理抖音主播
- 实时监控主播直播状态
- 开播短信通知（腾讯云短信）
- 完整的管理后台
- 监控日志记录

## 技术栈

- PHP 8.1+
- Laravel 10.x
- SQLite 数据库
- 腾讯云短信服务
- Bootstrap 5 前端框架

## 安装步骤

### 1. 环境要求

- PHP >= 8.1
- Composer
- SQLite 扩展

### 2. 安装依赖

```bash
composer install
```

### 3. 配置环境

复制 `.env.example` 为 `.env` 并配置相关参数：

```bash
cp .env.example .env
```

生成应用密钥：

```bash
php artisan key:generate
```

### 4. 配置腾讯云短信

在 `.env` 文件中配置腾讯云短信参数：

```
TENCENT_SMS_SECRET_ID=your_secret_id
TENCENT_SMS_SECRET_KEY=your_secret_key
TENCENT_SMS_APP_ID=your_app_id
TENCENT_SMS_SIGN_NAME=your_sign_name
TENCENT_SMS_TEMPLATE_ID=your_template_id
```

### 5. 初始化数据库

```bash
php artisan migrate
php artisan db:seed
```

### 6. 启动定时任务

```bash
php artisan schedule:work
```

### 7. 启动开发服务器

```bash
php artisan serve
```

访问 http://localhost:8000

## 默认管理员账号

- 邮箱: admin@example.com
- 密码: admin123

## 使用说明

### 用户端

1. 注册账号并登录
2. 在控制台添加需要监控的抖音主播
3. 设置手机号用于接收开播通知
4. 系统会自动检测主播直播状态
5. 当主播开播时，会收到短信通知

### 管理端

1. 使用管理员账号登录管理后台
2. 可以查看所有用户、主播和监控日志
3. 可以禁用/启用用户
4. 可以删除违规用户或主播

## 项目结构

```
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── MonitorLiveStatus.php    # 监控命令
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── AuthController.php        # 用户认证
│   │   ├── Admin/
│   │   │   └── AdminController.php       # 管理后台
│   │   └── DashboardController.php       # 用户中心
│   ├── Models/
│   │   ├── User.php                      # 用户模型
│   │   ├── Streamer.php                  # 主播模型
│   │   ├── MonitorLog.php                # 监控日志模型
│   │   └── Admin.php                     # 管理员模型
│   └── Services/
│       ├── DouyinService.php             # 抖音服务
│       └── SmsService.php                # 短信服务
├── config/                               # 配置文件
├── database/
│   ├── migrations/                       # 数据库迁移
│   └── seeders/                          # 数据填充
├── resources/
│   └── views/                            # 视图文件
├── routes/                               # 路由文件
└── public/                               # 公共目录
```

## 注意事项

1. 请确保腾讯云短信服务已正确配置
2. 监控频率设置为每秒一次，请根据实际需求调整
3. 抖音API可能会变化，需要及时更新检测逻辑
4. 建议在生产环境中使用MySQL数据库

## 许可证

MIT License