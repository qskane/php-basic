# HTTP 认证

- header() 函数来向客户端浏览器发送“Authentication Required”信息，使其弹出一个用户名／密码输入窗口
- PHP_AUTH_USER(用户名)，PHP_AUTH_PW(密码) 和 AUTH_TYPE(认证类型Basic|Digest) 保存于$_SERVER数组中。
 