<?php

namespace App\Http\Controllers\Appid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tests;
use App\Zhoukao;

class AppidController extends Controller
{
    public function logins_do()
    {
       $u_name=$_POST['u_name'];
       $u_pwd=$_POST['u_pwd'];
       $res=Tests::where(['u_name'=>$u_name,'u_pwd'=>$u_pwd])->first();
       if($res){
           session(['user'=>$res]);
           return redirect('user');
       }
    }

    public function user()
    {

        return view("appid.user");
    }

    public function shenqing(){
        $info=session("user");
        if($info==null){
            return json_encode(['font'=>'请去登录']);
        }
        $appid=rand(1000,9999)."a";
        $secret=rand(10000,99999)."s";
        $user_id=$info['test_id'];
        $res=Zhoukao::where(['test_id'=>$user_id])->first();
        if($res){
             $res=Zhoukao::where(['test_id'=>$user_id])->update(['appid'=>$appid,'secret'=>$secret]);
        }else{
            $res=Zhoukao::insert(['test_id'=>$user_id,'appid'=>$appid,'secret'=>$secret]);
        }
        if($res){
            return json_encode(['font'=>'成功','code'=>'1','appid'=>$appid,'secret'=>$secret]);
        }else{
            return json_encode(['font'=>'失败','code'=>'2']);
        }
    }

    public function diaoyong()

    {
        $info=session('user');
        $user_id=$info['test_id'];
        $res=Zhoukao::where(['test_id'=>$user_id])->first();

        $appid=$res['appid'];
        $secret=$res['secret'];
        $shuju=$appid.$secret;
//        return json_encode($shuju);
        \openssl_public_encrypt(
        //要加密数据
            $shuju,
            //加密指定参数
            $encrypy,
            //引入公钥
            \file_get_contents(public_path('/public.key')),
            //加密指定参数
            OPENSSL_PKCS1_PADDING

        );
        \openssl_private_decrypt(
        //解密数据
            $shuju,
            //解密指定数据
            $decrypt,
            //引入私钥
            \file_get_contents(public_path('/private.key')),
            //解密指定参数
            OPENSSL_PKCS1_PADDING
        );
        return json_encode($shuju);
    }



    public function a()
    {
        return view('appid.register');
    }
    public function b()
    {
        echo 1;
    }

}
//解密
/*
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


*/