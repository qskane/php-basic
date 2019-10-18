##预定义变量
https://www.php.net/manual/zh/reserved.variables.php

### 超全局变量
https://www.php.net/manual/zh/language.variables.superglobals.php

它们在一个脚本的全部作用域中都可用

#### $GLOBALS
- 包含了全部变量的全局组合数组。变量的名字就是数组的键
- $GLOBALS在PHP中总是可用的。

#### $_SERVER
https://www.php.net/manual/zh/reserved.variables.server.php
包含了诸如头信息(header)、路径(path)、以及脚本位置(script locations)等等信息的数组

#### $_GET
通过 URL 参数传递给当前脚本的变量的数组。

#### $_POST
当 HTTP POST 请求的 Content-Type 是 **application/x-www-form-urlencoded** 或 **multipart/form-data** 时，会将变量以关联数组形式传入当前脚本。

#### $_FILES
https://www.php.net/manual/zh/features.file-upload.post-method.php

确保文件上传表单的属性是 enctype="multipart/form-data"，否则文件上传不了
```html
<form enctype="multipart/form-data" action="__URL__" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="avatar" type="file" />
    <input type="submit" value="Send File" />
</form>
```
Error Messages Explained
https://www.php.net/manual/en/features.file-upload.errors.php


#### $_COOKIE
#### $_SESSION
#### $_REQUEST
默认情况下包含了 $_GET，$_POST 和 $_COOKIE 的数组。

#### $_ENV

### 其他预定义变量
$php_errormsg — 前一个错误信息
$HTTP_RAW_POST_DATA — 原生POST数据
$http_response_header — HTTP 响应头
$argc — 传递给脚本的参数数目
$argv — 传递给脚本的参数数组


##变量范围

- 函数内外不能皆互通
- 静态变量仅在局部函数域中存在，但当程序执行离开此作用域时，其值并不丢失。静态声明是在编译时解析的，如果在声明中用表达式的结果对其赋值会导致解析错误。





