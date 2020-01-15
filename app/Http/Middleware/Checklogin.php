<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Common;
use Illuminate\Support\Facades\Redis;
class Checklogin extends Common
{
    public $app_msg = [
        'z' => 'k',
        'y' => 'w'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $data = $request->post('data');

        $decrypt_data = $this->unbindthick($data);
        $decrypt_data = json_decode($decrypt_data, true);
        $check = $this->checkSing($decrypt_data);
        return $next($request);
    }

    public function checkSing($decrypt_data)
    {
        $client_sing = request()->post('sign');
        ksort($decrypt_data);
        if (isset($this->app_msg[$decrypt_data['appid']])) {
            $json = json_encode($decrypt_data) ."?appkey=". $this->app_msg[$decrypt_data['appid']];
            if ($client_sing == md5($json)) {
                //检查是否重放攻击
                if(Redis::sAdd('code_set',$decrypt_data['time'].$decrypt_data['rand'])){
                    return [
                        'code' => '200',
                        'font' => '成功',
                        'data' => md5($json)
                    ];
                }else{
                    return [
                        'code' => '4000',
                        'font' => '重放',
                    ];
                }

            } else {
                return [
                    'code' => '300',
                    'font' => '验签失败',

                ];
            }

        } else {
            return [
                'code' => '4000',
                'font' => '没有appid',
            ];
        }

    }







/*
    public function checkSign($res)
    {
//        var_dump($res);die;
        $client_sign=request()->post('sign');           //b7de67244dbe422cf2f4e81994dc4f79
        $client_sign=json_encode($client_sign);
//        return $client_sign;

        $res=$this->unbindthick($client_sign);
//        $res=json_encode($res);
//        var_dump($res);die;
//        return md5($res);
//        $kres=ksort($res);
//        var_dump($kres);die;
        $msg=$this->getMsg();


        if(isset($msg['appid'])){
           $json=json_encode($msg)."?appkey=".$msg['appkey'];
//            var_dump($json);die;
           if($client_sign==md5($json)){
               return [
                   'code'=>'200',
                   'font'=>'成功'
               ];
           }else{
               return [
                   'code'=>'401',
                   'font'=>'验签失败'
               ];
           }
        }else{
            return [
                'code'=>'400',
                'font'=>'请传递appid'
            ];
        }
    }
*/

}
