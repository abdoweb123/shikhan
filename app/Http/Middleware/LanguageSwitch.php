<?php

namespace App\Http\Middleware;

use App\libraries\Helpers;
use Closure;
use Symfony\Component\HttpFoundation\Request;
use App\language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App;
class LanguageSwitch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request);
        $lang=$request->lang;
        $alies=$request->alias;
        $alies=str_replace(' ','-',$alies);
            // dd($next($request));
        //return $alies;
        if(!$lang) {
            $lang = Helpers::defult_language();
            return redirect($lang->alies,301);
        }
        else{
            $lang=language::where('alies',$lang)->where('status',1)->first();
            if($lang==null)
                return redirect('404');
        }
        if(!session::has('Global_Language'))
            session::forget('Global_Language');
        session(['Global_Language'=>$lang->alies]);
        App::setLocale($lang->alies);
        $pviot="";
        $where=$request->segment(2);
        $where=explode(":",$where);
        $where=$where[0];
        if($where=='category'){
            $pviot='category_id';
            $table='category_description';
        }
        else if($where=='program'){
            $pviot='post_id';
            $table='post_description';
        }
        else if($where=='info'){
            $pviot='info_page_id';
            $table='info_page_description';
        }
        else{
            return $next($request);
        }
        $res=DB::table($table)->where('alies',$alies)->first();
//      return response()->json($res);
        if($res==null)
            return redirect('404');

        if($res->language_id != $lang->id){
            $route=DB::table($table)->where('language_id',$lang->id)->where($pviot,$res->{$pviot})->first();
            if($route == null)
                return redirect('/'.$lang->alies);
            if($where=='news')
                return redirect($lang->alies.'/'.$where.'/'.$route->alies);
            return redirect($lang->alies.'/'.$where.':'.$route->alies);
        }
        return $next($request);
    }
}
