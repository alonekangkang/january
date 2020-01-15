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

<img src="{{$rlu}}" alt=""  height="200px"  width="200px"  >


<form action="{{url('admin/admin_login_do')}}" method="post">

      @if(!empty($errors->first()  ))
            {{$errors->first()}}
      @endif

账号 <input type="text"  name="u_name"    > <br>
密码 <input type="password"  name="u_pwd" > <br>
    <input type="submit">

</form>
</body>
</html>
<script src="/jquery.js"></script>
<script>
    var sj="{{$sj}}";
    var sjian=setInterval("qing()",1000);
    function qing() {
        $.ajax({
            url:"{{url('admin/exam')}}",
            data:{sj:sj},
            dataType:"json",
            success:function (res) {
                if(res.code==1){

                    location.href="http://www.january.com/admin/lists";
                    alert("成功");
                }
            }
        })
    }

</script>