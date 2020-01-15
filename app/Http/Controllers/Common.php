<?php

namespace App\Http\Controllers;

class Common
{
    //获取微信TOKEN
    public function GetToken(){
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx8f3185c8718ef1b5&secret=030f1900793a53a2e668d9494ce6f7ff";
        $token_info=file_get_contents($url);
        $token_info=json_decode($token_info,true);
        $token=$token_info['access_token'];
        return $token;
    }
    //获取ticke
    public function GetTicke($token){
        $sj=md5(uniqid().time());
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;
        $data="{\"expire_seconds\": 60, \"action_name\": \"QR_STR_SCENE\", \"action_info\": {\"scene\": {\"scene_str\": \"$sj\"}}}";
        $res=$this->posts($url,$data);
        $res=json_decode($res);
        return $res;
    }
    //根据ticke获取二维码
    public function WeChat_qr_code($ticke){
        $url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticke;
        $res=file_get_contents($url);
        return $res;
    }
    public static function gets($url){
        //初始化： curl_init
        $ch = curl_init();
        //设置	curl_setopt
        curl_setopt($ch, CURLOPT_URL, $url);  //请求地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //返回数据格式
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //执行  curl_exec
        $result = curl_exec($ch);
        //关闭（释放）  curl_close
        curl_close($ch);
        return $result;
    }
    public  function posts($url,$postData){
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
        //执行  curl_exec
        $result = curl_exec($ch);
        //关闭（释放）  curl_close
        curl_close($ch);
        return $result;
    }

    public function error($url,$font){
//        var_dump(redirect($url)->withErrors($font)) ;
        return redirect($url)->withErrors($font);
    }

    //加密
    /*加密
     *  $data 要加密的明文（字符串格式） $method加密方式
     * $pwd 加密密钥        $options
     *          $options 数据格式选项（可选）【选项有：】
                    0
            OPENSSL_RAW_DATA=1
            OPENSSL_ZERO_PADDING=2
            OPENSSL_NO_PADDING=3
    $i 密初始化向量      如果$method为DES的话  则不需填
     */
    public  function addthick($data){
        if(is_array($data)){
            $data=json_encode($data);
        }
        $mi=openssl_encrypt(
            ($data),
            "AES-256-CBC",
           "1904api1",
            "1",
            "1904190419041904"
        );
        return base64_encode($mi);
    }
    //解密
    /*
     * $res 要解密的密文  解密方式    解密密钥
     * $options 数据格式选项（可选）【选项有：】
                    0
            OPENSSL_RAW_DATA=1
            OPENSSL_ZERO_PADDING=2
            OPENSSL_NO_PADDING=3
     $i 密初始化向量     则不需填
     * */
    public   function unbindthick($data){
        if(is_array($data)){
            $data=json_encode($data);
        }
//        var_dump($data);die;
        $res=openssl_decrypt(
            base64_decode($data),
            "AES-256-CBC",
            "1904api1",
            "1",
            "1904190419041904"
        );

        return $res;
    }
    //客户端需要将appid和appkey传到服务器进行验证
    public function getAppidAppkey()
    {
        return [
            'appid'=>'z',
            'appkey'=>'k'
        ];
    }
    //生成签名
    public function getSign($data,$appkey)
    {
        //将数据进行ksor排序
        ksort($data);
//        //转为json字符串
        $json_str=json_encode($data);
        return md5($json_str."?appkey=".$appkey);

    }

    //老师写的Curl
    public function teacher($url,array $data,$is_post=1){
        $ch=curl_init();
        //获取 id  和 key
        $app_safe=$this->getAppidAppkey();

        $data['appid']=$app_safe['appid'];
        //将随机数和时间戳加入数组   防止重放攻击
        $data['rand']=rand(100000,999999);
        $data['time']=time();
        //生成签名
        $all_data=[
            'data'=>$this->addthick($data),
            'sign'=>$this->getSign($data,$app_safe['appkey'])
        ];
        if($is_post){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$all_data);
        }else{
//            return "noPOST";
            $api_url=$url."?".http_build_query($data);
        }
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $data=curl_exec($ch);
        curl_close($ch);
//        return 111;
        return $data;

    }




    //只利用Curl的post方式发送  url和数据
    //老师写的Curl
    public function curlpost($url,  $postData,$is_post=1){
        if(is_array($postData)){
            $postData=json_encode($postData);
        }
        $ch=curl_init();
        if($is_post){
            curl_setopt($ch,CURLOPT_POST,1);
//            curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($postData));
            curl_setopt($ch,CURLOPT_POSTFIELDS,['data'=>$this->addthick($postData)]);
        }else{
            return "noPOST";
//            $api_url=$url."?".http_build_query($postData);
        }
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $postData=curl_exec($ch);
        curl_close($ch);
//        return 111;
        return $postData;

    }


    //curlget传递
    public static function get($url){

        //初始化： curl_init
        $ch = curl_init();
        //设置	curl_setopt
        curl_setopt($ch, CURLOPT_URL, $url);  //请求地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //返回数据格式
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //执行  curl_exec
        $result = curl_exec($ch);
        //关闭（释放）  curl_close
        curl_close($ch);
        return $result;
    }
    //Curl Post传输
    public static function post($url,$postData){
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
        //执行  curl_exec
        $result = curl_exec($ch);
        //关闭（释放）  curl_close
        curl_close($ch);
        return $result;
    }




}