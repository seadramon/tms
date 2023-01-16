<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EnsureSessionIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->has('sessid')){
            $this->generateSession($request->sessid);
        }
        if (session()->exists('TMP_WBSESSID') || Auth::check()) {
            $route = str_replace('.data', '.index', $request->route()->getName());
            $rm = RoleMenu::whereHas('role', function($sql){
                    $sql->where('grpid', session('TMP_ROLE'));
                })
                ->whereHas('menu', function($sql) use ($route) {
                    $sql->where('route_name', $route);
                })
                ->first();
            Session::put('TMS_ACTION_MENU', json_encode($rm->action_menu ?? []));
            return $next($request);
        }else{
            return redirect()->route('vendor.login');
        }
        // else{
        //     if(session()->has('TMP_WBSESSID')){
        //         $this->generateSession($request->sessid);
        //     }else{
        //         if(Auth::check())
        //     }
        // }
        
    }

    private function generateSession($session_id)
    {
        $log = DB::table('usradm.usr_log')->where('wbsesid', $session_id)->whereNull('logout')->count();
        if($log == 0){
            return redirect()->away(env('LOGIN_URL'));
        }
        $detail = DB::table('usradm.usr_log_d1')->where('wbsesid', $session_id)->get()->mapWithKeys(function($item){ return [$item->wbvarname => $item->wbvarvalue]; })->all();
        Session::put($detail);
        $role = DB::table('usradm.usr_role')
            ->where('usrid', session('TMP_USER'))
            ->where(function($sql){
                $sql->where('roleid', 'like', 'B%');
                $sql->orWhere('roleid', '#1');
            })
            ->first();
        Session::put('TMP_ROLE', $role->roleid ?? '');
    }
}
