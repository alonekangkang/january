<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Tests;
class Session_middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $info=session('info');
        if(!$info){
            return redirect('admin/admin_login');
        }
        //取到现在的session_id
        $session_id=Session::getId();
        //取到数据库中session_id
        $u_name=$info['u_name'];
        $u_pwd=$info['u_pwd'];
        //不同浏览器登录互踢
        $only=Tests::where(['u_name'=>$u_name,'u_pwd'=>$u_pwd])->first()->toarray();
        $login_time=$only['login_time'];
        $db_sessionid=$only['session_id'];
        if(!empty($db_sessionid)){
            if($db_sessionid!=$session_id){
                return redirect('admin/admin_login');
            }
        }
        if(time()>$login_time){
            return redirect('admin/admin_login');
        }else{
            Tests::where(['u_name'=>$u_name,'u_pwd'=>$u_pwd])->update(['login_time'=>time()+1200]);
        }
        return $next($request);
    }
}
