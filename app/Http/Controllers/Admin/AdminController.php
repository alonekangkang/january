<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tests;
use Session;
use App\Http\Controllers\Common;
use Cache;
class AdminController extends Common
{
    public function developer(Request $request){

        //echo $_GET['echostr'];
        //接收用户的xml数据包
        $poststr=file_get_contents("php://input");
        //处理xml格式信息，转为对象
        $postobj=simplexml_load_string($poststr,"SimpleXMLElement",LIBXML_NOCDATA);

        //用户openid
        $client=(string)$postobj->FromUserName;

        //开发者openid
        $server=(string)$postobj->FromUserName;

        if($postobj->MsgType=='event' && $postobj->Event=='subscribe'){
            $EventKey=(string)$postobj->EventKey;
            $EventKey=(string)ltrim($EventKey,'qrscene_');
            Cache::put($EventKey,$client);
            Cache::get($EventKey);
            echo "<xml>
                          <ToUserName><![CDATA[".$client."]]></ToUserName>
                          <FromUserName><![CDATA[".$server."]]></FromUserName>
                          <CreateTime>".time()."</CreateTime>
                          <MsgType><![CDATA[text]]></MsgType>
                          <Content><![CDATA[刚刚关注]]></Content>
                       </xml>
                        ";
            }
            if($postobj->MsgType=='event'&&$postobj->Event=='SCAN'){
                $EventKey=(string)$postobj->EventKey;
                Cache::put($EventKey,$client);
                echo "<xml>
                          <ToUserName><![CDATA[".$client."]]></ToUserName>
                          <FromUserName><![CDATA[".$server."]]></FromUserName>
                          <CreateTime>".time()."</CreateTime>
                          <MsgType><![CDATA[text]]></MsgType>
                          <Content><![CDATA[关注过  在关注]]></Content>
                       </xml>
                        ";
            }
    }

    public function admin_login()
    {
//        获取token
        $token=$this->GetToken();
//        echo $token;die;
//        dd($token);
        //根据token获取ticke 返回一个对象

        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;
        $sj=md5(uniqid().time());
        $data="{\"expire_seconds\": 60, \"action_name\": 
        \"QR_STR_SCENE\", \"action_info\": {\"scene\": {\"scene_str\": \"$sj\"}}}";
        $res=$this->posts($url,$data);
        $tickeInfo=json_decode($res);
        $ticke=$tickeInfo->ticket;
        $url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticke;
        echo $sj;
        return view('admin/admin_login',['rlu'=>$url,'sj'=>$sj]);
    }
    //检查有没有扫码 （检查缓存中是否有扫码的用户openid）
    public function examOpen(){
        $sj=request()->get("sj");
        $a=Cache::get($sj);
        if(!$a){
            return json_encode(['font'=>'未扫码','code'=>0]);
        }
        return json_encode(['font'=>'正在跳转','code'=>1]);
    }

    public function admin_login_do()
    {

        $session_id=Session::getId();
        $data = request()->except("_token");
//        dd($data);
        if(empty($data['u_name'])){
//             $a=$this->error("admin/admin_login","请输入账号") ;
//             dd($a);
            return redirect('admin/admin_login')->withErrors('请输入账号');
        }

        if(empty($data['u_pwd'])){
            return redirect('admin/admin_login')->withErrors('请输入密码');
        }
        $only = Tests::where(['u_name' => $data['u_name']])->first();
        $u_name = $data['u_name'];
        $u_pwd = $data['u_pwd'];
        $user_id = $only['test_id'];

        if (!empty($only)){
            $error_num = $only['error_num'];
            $error_time = $only['error_time'];
            $time = time();
            if ($only['u_pwd'] == $u_pwd) {
                if ($error_num >= 3 && $time - $error_time < 3600) {
                    $min = 60 - floor(($time - $error_time) / 60);
                    echo "账号锁定中，" . $min . "分钟后重新登录";
                    exit;
                } else {
                    //登录成功
                    $arr=['u_name'=>$u_name,'u_pwd'=>$u_pwd];
                    session(['info'=>$arr]);
                    // 清零
                    $login_time=time()+1200;
                    $res = Tests::where('test_id', $user_id)->update([
                        'error_num' => 0,
                        'error_time' => null
                    ]);
                    Tests::where(['test_id'=>$user_id])->update([
                        'session_id'=>$session_id,
                        'login_time'=>$login_time
                    ]);
                    return redirect('admin/lists');
                }

            } else {
                // 密码错误
                if ($time - $error_time >= 3600) {
                    $res = Tests::where('test_id', $user_id)->update(['error_num' => 1, 'error_time' => $time]);
                    echo "密码错误，您还有2次机会";
                } else {
                    if ($error_num >= 3) {
                        echo "您的账号已锁定，请于一小时后重试";
                    } else {
                        $res =Tests::where('test_id', $user_id)->update(['error_num' => $error_num + 1, 'error_time' => $time]);
                        if ($res) {
                            if((3-$error_num+1)!=0){
                                echo "密码错误，您还有" . (3 - ($error_num + 1)) . "此机会";die;
                            }else{
                                echo "已锁定";die;
                            }
                        }
                    }
                }


            }
            /*
            if($only){
                $time = time();
                $u_pwd = $only['u_pwd'];
                $error_time = $only['error_time'];
                $error_num = $only['error_num'];
                if($data['u_pwd']==$only['u_pwd']){
                //密码正确
                    if($error_num>=3&&$time-$error_time-$error_time<3600){
                        $min=60-floor($time-$error_time/60);
                        return "请于分钟后".$min."登录";die;
                    }else{
                        Tests::where(['u_name'=>$data['u_name']])
                               ->update(['error_num'=>0,'error_time'=>null]);
                    }
                }else{
                    if($time-$error_time>3600){
                        Tests::where(['u_name'=>$data['u_name']])
                               ->update(['error_num'=>1,'error_time'=>$time]);
                    }else{
                        if($error_num>=3){
                            return "已锁定 一小时后登录";
                        }else{
                            Tests::where(['u_name'=>$data['u_name']])
                                   ->update(['error_num'=>$error_num+1,'error_time'=>$time]);
                            $a=3-$error_num+1;
                            return "还有".$a."次机会";
                        }
                    }
                }
            }

    */

        }


    }
    public function lists(){

        return view('admin.lists');
    }



}
