<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tests;
//继承公共类    Common
use App\Http\Controllers\Common;
class IndexController extends Common
{
    /*
     * http_build_query 将参数作为   ***=***&***=***&***=***
     */
    public function login_do()
    {
        echo 111;die;
       $postData=[
           'u_name'=>'6514165165',
           'u_pwd'=>'666'
       ];
        $url="http://api.zhangkang.com/login_do";

        //调用Common中的Curl方法
        $res=$this->teacher($url,$postData);
        print_r($res);die;


    }
    //私钥
    public function si()
    {
        return "-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDZitcWyDzv3o9ZmfwhiTWIQwOUtUK/DvYgj+zW2rIhKc+5QvFk
QAtV4pd3wnZ7mDw3R6y3EdHqKDHhaBpdd7Oq8FydKplYwXUtiqCoD1tvIvz/YXqL
iZh7bVVO55CeVxKoo6mdz0SjLR290ohSWQzqcamrwCEuElPUeouZW/40xwIDAQAB
AoGBAJzlsRmiU6jhCNyj3Z/GWRCs3JFNZhVsUgHMLBIN6zlV8ZZ5fKZENqi742ih
nVioxI1OKXhj5tUOJmOe9J0C71X+NXT/JQBcIYvalP8D+LIqlWrJ/zYkopoYfnvP
MFnBpw3sUvYMUYHcMFR3jOdam0wD08hBzCywllNPpmHmZljpAkEA+v70Crfr83bp
1O9GbADKatqUrrrA3zkRroJ26eFqI2IVxoa+5N3c8DK/nfWJwsQolCQZXObEvSUu
+PgyDu2s8wJBAN3hJQ55MizgmdW9jEojH3NehaFFiNyIM+YLHsI7TQo0MlToPBSy
nqBuZhoXo7kDhUqpWCVSC18DInRQRi1+Pd0CQHmU+p4ejZs35PkSluhGUccE7rTd
HgSDXn9MD1InsQRGxQmPx/SRTC7GRm+7uumvn4BzJB4OYwrEckaD46u7keUCQQCE
TsVyJAMvj58sPaNycg9HFI5K5NP/7ZhFDUyCNipycz2wM+vfy8Oblzl7Ra1zng3V
v7W4S3xMY+ofwd6XMjhhAkAvKUF3fWBWh2iw4feiKN4Ajqx3a/vOe9/9knY6afvq
FYlLgHbcwIRDwj6B7tNP2YhLHj2c+stpTPZOiim8dfkz
-----END RSA PRIVATE KEY-----
";
    }
    //公钥
    public function gong(){
        return "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDZitcWyDzv3o9ZmfwhiTWIQwOU
tUK/DvYgj+zW2rIhKc+5QvFkQAtV4pd3wnZ7mDw3R6y3EdHqKDHhaBpdd7Oq8Fyd
KplYwXUtiqCoD1tvIvz/YXqLiZh7bVVO55CeVxKoo6mdz0SjLR290ohSWQzqcamr
wCEuElPUeouZW/40xwIDAQAB
-----END PUBLIC KEY-----
";
    }

    public function test(){
            $str=str_repeat("111",25);
            $i=0;
            $all='';
            while($sub_str=substr($str,$i,117)){
                \openssl_public_encrypt(
                //要加密数据
                    $sub_str,
                    //加密指定参数
                    $encrypy,
                    //引入公钥
                    \file_get_contents(public_path('/public.key')),
                    //加密指定参数
                    OPENSSL_PKCS1_PADDING

                );
                $all.=$encrypy;
                $i+=117;
            }

           $jiemi=base64_encode($all);
           var_dump($jiemi);
           echo "<hr>";
           $i="0";
           $all='';
           $jiemi=base64_decode($jiemi);
           while($sub_str=substr($jiemi,$i,128)){
               \openssl_private_decrypt(
               //解密数据
                   $sub_str,
                   //解密指定数据
                   $decrypt,
                   //引入私钥
                   \file_get_contents(public_path('/private.key')),
                   //解密指定参数
                   OPENSSL_PKCS1_PADDING
               );
               $all.=$decrypt;
               $i+=128;
           }

           var_dump($all);

    }

















    //CURL    POST方式        现在无用
/*
    public function Wpost($url,$postData,$is_post=1){
        $postUrl = $url;
        $curlPost = $postData;
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($curl);//运行curl
        curl_close($curl);
        return $data;


}
    //Curl Post传输
    public   function CurlPost($url,$postData){
        //初始化： curl_init
        $ch = curl_init();
        //设置	curl_setopt
        curl_setopt($ch, CURLOPT_URL, $url);  //请求地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //返回数据格式
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //设置自动重定向
        //访问https网站 关闭ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //执行  curl_exec
        $result = curl_exec($ch);
        //关闭（释放）  curl_close
        curl_close($ch);
        return $result;
    }
*/
}
