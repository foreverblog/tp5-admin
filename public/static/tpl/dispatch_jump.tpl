<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="UTF-8" />
    <title>跳转提示</title>
</head>
<body>
<input type="hidden" id="msg" name="msg" value="<?php echo(strip_tags($msg));?>">
<input type="hidden" id="url" name="url" value="<?php echo($url);?>">
<input type="hidden" id="wait" name="wait" value="<?php echo($wait);?>">
<input type="hidden" id="code" name="code" value="<?php echo($code);?>">
<script src="__ADMIN__/layui/layui.js"></script>
    <script type="text/javascript">
        layui.use('layer', function(){
            var $ = layui.$; //由于layer弹层依赖jQuery，所以可以直接得到
            var msg=$("#msg").val();
            var url=$("#url").val();
            var wait=$("#wait").val();
            var code=$("#code").val();
            if(code==0){
                code=2;
            }else{
                code=1;
            }
            layer.msg(msg, {
                icon: code,
                time: 1500 //2秒关闭（如果不配置，默认是3秒）
            }, function(){
                location.href = url;
            });
            var interval = setInterval(function(){
                var time = --wait;
                if(time <= 0) {
                    location.href = url;
                    clearInterval(interval);
                };
            }, 1000);

        });
    </script>
</body>
</html>