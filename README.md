# 高性能Tracker服务框架

基于webman高性能框架，运行于php-cli模式的常驻内存高性能框架；

1、高稳定性。webman基于workerman开发，workerman一直是业界bug极少的高稳定性socket框架。

2、超高性能。webman性能高于传统php-fpm框架10-100倍左右，比go的gin echo等框架性能高1倍左右。

3、高复用。无需修改，可以复用现有composer生态。

4、高扩展性。支持自定义进程，可以做workerman能做的任何事情。

5、超级简单易用，学习成本极低，代码书写与传统框架没有区别。

6、支持独立部署，使用二进制php静态文件，无需PHP环境即可直接运行。

7、使用最为宽松友好的MIT开源协议。

## 优化点

1、Redis缓存全量的种子和用户的数据库重要字段；

2、计算上传下载量时，在内存完成；

3、有变动的种子数据，使用异步队列批量更新；

4、有变动的用户数据，使用节流算法（固定窗口）定时更新；

## 运行环境

Linux、Nginx 1.24+、PHP8.3.15+、MySQL 5.7.41+、Redis 7.0.0+

PHP扩展：ext-redis

## 技术栈

- PHP多进程
- Linux事件EPoll非阻塞IO
- 毫秒定时器
- 异步Socket
- 异步Redis
- 异步HTTP
- 异步消息队列

## 核心开发

- david

## 鸣谢

- 微信通知：爱语飞飞 https://iyuu.cn/
- webman：超高性能可扩展PHP框架 https://www.workerman.net/doc/webman/
- webman-admin：开源免费管理后台 https://www.workerman.net/doc/webman-admin/
- Layui：开源模块化前端 UI 组件库 https://layui.gitee.io/v2/docs/
- AgsvPT：https://www.agsvpt.com/
- OurBits：https://ourbits.club/
- R酱：https://blog.rhilip.info/
