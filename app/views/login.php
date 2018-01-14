<!DOCTYPE html>
<html lang="en" class="no-js"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>后台管理系统</title>
    <link rel="stylesheet" type="text/css" href="<?=asset('assets/login')?>/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="<?=asset('assets/login')?>/css/demo.css">
    <!--必要样式-->
    <link rel="stylesheet" type="text/css" href="<?=asset('assets/login')?>/css/component.css">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link id="layuicss-layer" rel="stylesheet" href="<?=asset('plugins/layui/2.2.5/css/modules/layer/default/layer.css')?>" media="all"></head>
<body>
<div class="container demo-1">
    <div class="content">
        <div id="large-header" class="large-header" style="height: 394px;">
            <canvas id="demo-canvas" width="1536" height="394"></canvas>
            <div class="logo_box">
                <h3>后台管理系统</h3>
                <form action="" name="f" method="post">
                    <div class="input_outer">
                        <span class="u_user"></span>
                        <input name="logname" class="text name" style="color: #FFFFFF !important" type="text" placeholder="请输入账户">
                    </div>
                    <div class="input_outer">
                        <span class="us_uer"></span>
                        <input name="logpass" class="text password" style="color: #FFFFFF !important; position:absolute; z-index:100;" value="" type="password" placeholder="请输入密码">
                    </div>
                    <div class="mb2"><a class="act-but submit" href="javascript:;" style="color: #FFFFFF">登录</a></div>
                </form>
            </div>
        </div>
    </div>
</div><!-- /container -->
<script src="<?=asset('assets/login')?>/js/tweenlite.min.js"></script>
<script src="<?=asset('assets/login')?>/js/easepack.min.js"></script>
<script src="<?=asset('assets/login')?>/js/raf.js"></script>
<script src="<?=asset('assets/login')?>/js/demo-1.js"></script>
<script src="<?=asset('plugins/layui/2.2.5/layui.js')?>"></script>
<script>
    layui.use(['layer'],function () {
        var layer = layui.layer;
        var $ = layui.jquery;
        $('.submit').click(function () {
            index = layer.load(1);
            name = $('.name').val();
            password = $('.password').val();
            $.ajax({
                type:'POST',
                url:"/index/login/tologin.html",
                data:{name:name,password:password},
                success:function (result) {
                    if(!result.code){
                        layer.msg(result.msg);
                        layer.close(index);
                    }else{
                        layer.msg(result.msg);
                        layer.close(index);
                        setTimeout(function () {
                            //window.location.href='http://localhost/index';
                            window.location.href='http://www.wulic.com/index';
                        },1500)
                    }
                }
            });
        });
    });
</script>
</body>
</html>