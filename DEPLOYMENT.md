# 部署说明

## 环境准备

### 1. 安装 PHP 环境
确保你的系统已安装 PHP 8.1 或更高版本。

### 2. 安装 Composer
下载并安装 Composer：https://getcomposer.org/download/

### 3. 安装项目依赖

```bash
composer install
```

## 配置步骤

### 1. 生成应用密钥

```bash
php artisan key:generate
```

### 2. 配置环境变量

编辑 `.env` 文件，配置以下参数：

```env
APP_NAME="抖音直播检测工具"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# 腾讯云短信配置（必须配置）
TENCENT_SMS_SECRET_ID=your_secret_id
TENCENT_SMS_SECRET_KEY=your_secret_key
TENCENT_SMS_APP_ID=your_app_id
TENCENT_SMS_SIGN_NAME=your_sign_name
TENCENT_SMS_TEMPLATE_ID=your_template_id
```

### 3. 初始化数据库

```bash
# 创建数据库文件
touch database/database.sqlite

# 运行数据库迁移
php artisan migrate

# 填充初始数据（创建管理员账号）
php artisan db:seed
```

## 启动服务

### 开发环境

```bash
# 启动开发服务器
php artisan serve

# 启动定时任务（新开一个终端）
php artisan schedule:work
```

### 生产环境

使用 Supervisor 管理定时任务：

创建配置文件 `/etc/supervisor/conf.d/douyin-monitor.conf`：

```ini
[program:douyin-monitor]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan schedule:work
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/schedule.log
```

启动 Supervisor：

```bash
supervisorctl reread
supervisorctl update
supervisorctl start douyin-monitor:*
```

## 访问系统

- 用户端：http://your-domain.com
- 管理端：http://your-domain.com/admin

默认管理员账号：
- 邮箱：admin@example.com
- 密码：admin123

## 腾讯云短信配置

### 1. 开通腾讯云短信服务

访问 https://console.cloud.tencent.com/sms

### 2. 创建短信签名和模板

**签名示例：**
- 签名名称：抖音直播检测
- 签名类型：网站
- 签名来源：已备案网站

**模板示例：**
```
模板名称：开播通知
模板内容：您关注的主播{1}正在直播，点击观看：{2}
参数：{1}为主播名称，{2}为直播链接
```

### 3. 获取 API 密钥

在腾讯云控制台获取：
- SecretId
- SecretKey
- 短信应用ID（AppId）

### 4. 配置到 .env 文件

将获取的信息填入 `.env` 文件中的对应配置项。

## 注意事项

1. **监控频率**：当前设置为每秒检测一次，请根据实际需求和服务器性能调整
2. **API 限制**：注意腾讯云短信的发送频率限制，避免触发限流
3. **日志监控**：定期检查 `storage/logs` 目录下的日志文件
4. **数据库备份**：定期备份 SQLite 数据库文件
5. **安全性**：生产环境请务必关闭 `APP_DEBUG`，修改默认管理员密码

## 故障排查

### 1. 短信发送失败

检查：
- 腾讯云短信配置是否正确
- 短信余额是否充足
- 签名和模板是否已审核通过
- 手机号格式是否正确

### 2. 监控不工作

检查：
- 定时任务是否正常运行
- 数据库中的主播数据是否正确
- 抖音API是否可访问

### 3. 数据库错误

检查：
- SQLite 文件是否存在
- 文件权限是否正确
- 数据库是否已正确迁移

## 性能优化建议

1. 使用 Redis 缓存
2. 使用 MySQL 替代 SQLite
3. 使用队列处理短信发送
4. 优化监控频率
5. 添加 CDN 加速静态资源

## 安全建议

1. 修改默认管理员密码
2. 启用 HTTPS
3. 配置防火墙规则
4. 定期更新依赖包
5. 设置文件权限

## 技术支持

如有问题，请查看日志文件或联系技术支持。