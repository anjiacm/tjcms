<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content=" black "/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="language" content="zh_cn"/>
    <title>登录</title>
    <link rel="stylesheet" href="<?php echo $this->_static_public ?>/tjm/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $this->_static_public ?>/tjm/css/login.css">
</head>
<body>
<form action="/?r=admin/default/login" method="post" name="LoginForm" class="form common-form">
    <legend class="form-tit">
        <h2>男人影院统计后台</h2>
    </legend>
    <div class="form-group">
        <input type="text" id="kr-shield-username" class="form-control" name="LoginForm[username]" placeholder="账号" required="">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" name="LoginForm[password]" placeholder="密码" required="">
    </div>
    <button id="kr-shield-submit" type="submit" class="btn btn-primary">立即登录</button>
</form>
<script src="<?php echo $this->_static_public ?>/tjm/js/canvas-nest.min.js"></script>
</body>
</html>