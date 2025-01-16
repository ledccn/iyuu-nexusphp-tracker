# push
webman push plugin  https://www.workerman.net/plugin/2

原版地址：https://github.com/webman-php/push

## 安装
```shell
composer require ledc/push
```


## 简介

一个免费的推送服务端插件，客户端基于订阅模式，兼容 [pusher](https://pusher.com/)，拥有众多客户端如JS、安卓(java)、IOS(swift)、IOS(Obj-C)、uniapp、.NET、 Unity、Flutter、AngularJS等。后端推送SDK支持PHP、Node、Ruby、Asp、Java、Python、Go、Swift等。客户端自带心跳和断线自动重连，使用起来非常简单稳定。适用于消息推送、聊天等诸多即时通讯场景。

插件中自带一个网页js客户端push.js以及uniapp客户端`uniapp-push.js`，其它语言客户端在 https://pusher.com/docs/channels/channels_libraries/libraries/ 下载


## 进度条

### HTML部分

```html
<!-- 顶 部 右 侧 菜 单 -->
<li class="layui-nav-item layui-hide-xs" title="长连接状态">
    <a href="#" class="layui-icon layui-icon-wifi" style="color: #ff5722;" id="websocket_state"></a>
</li>

<!-- 进度条 -->
<div id="progress-layer-wrapper" style="display: none;">
    <div class="layui-card">
        <div class="layui-card-header">执行数：<span class="layui-badge-rim"
                                                    id="progress-layer-count">0</span> / 总数：<span
                class="layui-badge-rim" id="progress-layer-total">0</span></div>
        <div class="layui-card-body">
            <div class="layui-progress layui-progress-big" lay-filter="filter-progress">
                <div class="layui-progress-bar" lay-percent="0%"></div>
            </div>
        </div>
    </div>
</div>
<script src="/plugin/ledc/push/push.js"></script>
```

### js部分

```javascript
layui.use(["jquery", "popup", "notice", "element"], function () {
    let $ = layui.$;
    let notice = layui.notice;
    let element = layui.element;
    // 渲染进度条组件
    element.render('progress', 'filter-progress');

    <?php
    $app_key = config('plugin.ledc.push.app.app_key');
    $websocket_port = parse_url(config('plugin.ledc.push.app.websocket'), PHP_URL_PORT);
    $auth = config('plugin.ledc.push.app.auth'); 
    ?>

    const hostname = location.hostname;
    const host = location.host;

    /**
     * 与服务器长链接通信
     */
    function connect() {
        let connection = new Push({
            url: (location.protocol === 'https:' ? 'wss://' : 'ws://') + (-1 !== host.indexOf(':') ? hostname + ':<?=$websocket_port?>' : hostname), // websocket地址
            app_key: '<?=$app_key?>',
            auth: '<?=$auth?>',
        });
        //长链接状态
        setInterval(() => {
            if (connection.connection.state === 'connected') {
                document.getElementById('websocket_state').style.color = '#16b777';
            } else {
                document.getElementById('websocket_state').style.color = '#ff5722';
                notice.error('长链接断线');
            }
        }, 1000);
        let channel = connection.subscribe('private-webman-admin');
        let dispatcher = connection.subscribe('dispatcher');
        dispatcher.on('notify', function (data) {
            console.info(data)
        });

        // 普通消息
        channel.on('message', function (data) {
            console.info(data)
        });

        // 进度条
        channel.on('progress', function (data) {
            const type = data.type;
            const count = data.success + data.fail;
            const total = data.total;
            const percent = count + ' / ' + total;
            // 设置进度值
            element.progress('filter-progress', percent);
            document.getElementById('progress-layer-count').innerText = count;
            document.getElementById('progress-layer-total').innerText = total;
            // 捕获页面元素，弹出进度条
            layer.open({
                type: 1,
                id: 'progress-layer' + type,
                title: '任务进度条',
                area: '520px',
                shade: false, // 不显示遮罩
                content: $('#progress-layer-wrapper'), // 捕获的元素
                success: function (layero, index, that) {
                    // 弹层的最外层元素的 jQuery 对象
                    console.log(layero);
                    // 弹层的索引值
                    console.log(index);
                    // 弹层内部原型链中的 this --- 2.8+
                    console.log(that);
                },
                end: function () {
                    // layer.msg('关闭后的回调', {icon:6});
                }
            });
        });
        // 通知
        channel.on('notify', function (data) {
            switch (data.type) {
                case 'success':
                    notice.success(data.msg);
                    break;
                case 'error':
                    notice.error(data.msg);
                    break;
                case 'warning':
                    notice.warning(data.msg);
                    break;
                case 'info':
                    notice.info(data.msg);
                    break;
                default:
                    notice.clear();
                    break;
            }
        });
    }

    connect();
});
```

