# API 文档

## 用户认证

### 注册
```
POST /register
Content-Type: application/x-www-form-urlencoded

参数：
- name: 用户名
- email: 邮箱
- password: 密码
- password_confirmation: 确认密码
- phone: 手机号（可选）
```

### 登录
```
POST /login
Content-Type: application/x-www-form-urlencoded

参数：
- email: 邮箱
- password: 密码
```

### 登出
```
POST /logout
需要认证
```

## 用户中心

### 获取控制台数据
```
GET /dashboard
需要认证
```

### 添加主播
```
POST /dashboard/streamer
需要认证

参数：
- name: 主播名称
- douyin_url: 抖音主页链接
```

### 删除主播
```
DELETE /dashboard/streamer/{id}
需要认证
```

### 切换监控状态
```
POST /dashboard/streamer/{id}/toggle
需要认证
```

### 立即检测
```
POST /dashboard/streamer/{id}/check
需要认证
```

### 获取设置
```
GET /settings
需要认证
```

### 更新设置
```
POST /settings
需要认证

参数：
- name: 用户名
- phone: 手机号
```

## 管理后台

### 管理员登录
```
POST /admin/login
Content-Type: application/x-www-form-urlencoded

参数：
- email: 邮箱
- password: 密码
```

### 获取概览数据
```
GET /admin
需要管理员认证
```

### 获取用户列表
```
GET /admin/users
需要管理员认证
```

### 切换用户状态
```
POST /admin/users/{id}/toggle
需要管理员认证
```

### 删除用户
```
DELETE /admin/users/{id}
需要管理员认证
```

### 获取主播列表
```
GET /admin/streamers
需要管理员认证
```

### 删除主播
```
DELETE /admin/streamers/{id}
需要管理员认证
```

### 获取监控日志
```
GET /admin/logs
需要管理员认证
```

## 定时任务

### 监控直播状态
```
php artisan monitor:live
```

该命令会：
1. 获取所有正在监控的主播
2. 检测每个主播的直播状态
3. 记录监控日志
4. 如果主播开始直播，发送短信通知

## 响应格式

### 成功响应
```json
{
    "success": true,
    "message": "操作成功",
    "data": {}
}
```

### 错误响应
```json
{
    "success": false,
    "message": "错误信息",
    "errors": {}
}
```

## 认证方式

### 用户认证
使用 Laravel 的 Session 认证，登录后会在 Session 中保存用户信息。

### 管理员认证
使用 Laravel 的 Guard 机制，使用 `admin` guard 进行认证。

## 数据模型

### User（用户）
```json
{
    "id": 1,
    "name": "用户名",
    "email": "user@example.com",
    "phone": "13800138000",
    "is_active": true,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### Streamer（主播）
```json
{
    "id": 1,
    "user_id": 1,
    "name": "主播名称",
    "douyin_url": "https://www.douyin.com/user/...",
    "douyin_id": "MS4wLjABAAAA...",
    "is_monitoring": true,
    "is_live": false,
    "last_live_time": null,
    "last_check_time": "2024-01-01T00:00:00.000000Z",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### MonitorLog（监控日志）
```json
{
    "id": 1,
    "user_id": 1,
    "streamer_id": 1,
    "was_live": false,
    "is_live": true,
    "notification_sent": true,
    "response_data": "{}",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### Admin（管理员）
```json
{
    "id": 1,
    "name": "管理员",
    "email": "admin@example.com",
    "is_active": true,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

## 注意事项

1. 所有需要认证的接口都需要先登录
2. 管理后台接口需要管理员权限
3. 监控频率默认为每秒一次
4. 短信发送需要腾讯云短信服务配置
5. 抖音API可能会变化，需要及时更新检测逻辑