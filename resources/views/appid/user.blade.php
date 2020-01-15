<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
 <button  name="shenqing"  > 申请权限 </button>

<button name="diaoyong">去调用接口</button>
</body>
</html>
<script src="/jquery.js"></script>
<script>
    //点击申请权限
$(document).on("click","[name='shenqing']",function () {
      $.ajax({
          url:"http://www.appid.com/shenqing",
          dataType:"json",
          type:"get",
          success:function (res) {
              alert(res.font);
          }
      })
});
//点击调用接口
    $(document).on("click","[name='diaoyong']",function () {
        $.ajax({
            url:"http://www.appid.com/diaoyong",
            dataType:"json",
            type:"get",
            success:function (res) {
                    console.log(res)
            }
        })
    })
</script>