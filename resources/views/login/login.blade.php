<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

   账号 <input type="text" name="u_name"   > <br>
    密码 <input type="text"   name="u_pwd" > <br>
  <button id="register">登录</button>
    

</body>
</html>
<script src="/jquery.js"></script>
<script>
    //ajax 表单令牌   直接复制到script中
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   $(document).on("click","#register",function () {
       var u_name=$("[name='u_name']").val();
       var u_pwd=$("[name='u_pwd']").val();
       $.ajax({
           url:"http://api.zhangkang.com/login_do",
           data:{u_name:u_name,u_pwd:u_pwd},
           type:"get",
           dataType:"json",
           success:function (res) {
               console.log(res);
           }
       })
   })
</script>