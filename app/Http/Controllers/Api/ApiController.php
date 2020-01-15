<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tests;
//解决跨域
header('Access-Control-Allow-Origin: *');
use App\Http\Controllers\Common;
class ApiController extends Common
{
    public function login_do(){
        $postData=[
            'u_name'=>'6514165165',
            'u_pwd'=>'666'
        ];
        $url="http://api.zhangkang.com/login_do";
        //调用Common中的Curl方法
        $res=$this->teacher($url,$postData);
        print_r($res);
    }


}