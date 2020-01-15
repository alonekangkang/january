<?php
namespace App\Http\Controllers;


class TestController  extends Controller {
    const method = "AES-256-CBC";
    const pwd= "sdds";
    const i= "1904190419041914";
    const options= 0;
    public function test()
    {

        $data="1";
//       / $data=json_encode($data);
        $method="AES-256-CBC";
        $pwd="sdds";
        $i="1904190419041904";
        $options=0;
        $res=$this->addthick($data,$method,$pwd,$options,$i);
        echo($res);
        echo "<hr>";
        $rr=$this->unbindthick($res,$method,$pwd,$options,$i);
//        $rr=json_decode($rr);
        print_r($rr);
    }
    //加密
    public  function addthick($data,$method,$pwd,$options,$i){
        $mi=openssl_encrypt(
            $data,
            $method,
            $pwd,
            $options,
            $i
            );
        return $mi;
    }
    //解密
    public   function unbindthick($res,$method,$pwd,$options,$i){
        $a=openssl_decrypt($res, $method, $pwd, $options, $i);

        return $a;
    }
    //登陆试图
    public  function login(){
        return view("login.login");
    }
    //接收数据
    public function login_do(){
        $data=request()->except("_token");
        $data=json_encode($data);
        $res=$this->addthick($data,self::method,self::pwd,self::options,self::i);
        dd($res);
//        print_r($data);
//        $this->addthick();
    }
}