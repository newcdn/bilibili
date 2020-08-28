# 修改说明
原作者：京都一只喵
1. 解密 `yzmplayer.js` 文件
2. 修复了视频弹幕非独立的问题
3. 兼容了 PHP7.X，在 PHP7.4 环境测试通过
4. 更新版本号至 v1.2.1
5. 重写了使用说明

# 使用方法
1. 解压到网站根目录
2. 登录  你的域名/dmku 进行配置数据库  
3. 修改播放器后台密码  dmku/config.inc.php
4. 登录后台 你的域名/admin  密码为第3步修改的密码
5. 播放器功能可后台设置

# 参数说明（player/index.php）
``` 
"av":'<?php echo($_GET['av']);?>',//B站av号，用于调用弹幕
"url":"<?php echo($_GET['url']);?>",//视频链接
"id":"<?php echo($_GET['url']);?>",//视频id
"sid":"<?php echo($_GET['sid']);?>",//集数id
"pic":"<?php echo($_GET['pic']);?>",//视频封面
"title":"<?php echo($_GET['name']);?>",//视频标题
"next":"<?php echo($_GET['next']);?>",//下一集链接
"user": '<?php echo($_GET['user']);?>',//用户名
"group": "<?php echo($_GET['group']);?>",//用户组
```
# 请求示例
#### 基本
http://localhost/player/?url=https://cdn.jsdelivr.net/gh/MoGuJ/Video-Bed/Your.Name/playlist.m3u8

#### 高级
除了 url 参数，其他都可以省略

http://localhost/player/?url=https://cdn.jsdelivr.net/gh/MoGuJ/Video-Bed/Your.Name/playlist.m3u8&next=https://cdn.jsdelivr.net/gh/MoGuJ/Video-Bed/Your.Name/playlist.m3u8&sid=1&pic=https://img.xx.com/1.png&user=游客&group=1&name=测试
