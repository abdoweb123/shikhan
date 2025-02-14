<?php

namespace App\Http\Middleware;
use Closure;
use DB;

class MustChangeName
{

	public function handle($request, Closure $next)
	{

			if(auth()->check()){
				if( mb_strlen(auth()->user()->name) <= 7 ){ // config('project.max_user_name_chr')
//						$request->session()->flash('userMustChangeName', 'true'); // just name less than X chr // return redirect()->route('profile');
                    $this->nameDubliacted($request);
                    $this->prevented($request);
				} else {
                    $this->nameDubliacted($request);
                    $this->prevented($request);
                    }
			}

			return $next($request);

	}

	public function nameDubliacted($request)
	{
			// if( DB::Table('members')->where('id', '!=', auth()->id())->where('name', auth()->user()->name)->wherenull('deleted_at')->exists() ){
			// 	$request->session()->flash('userMustChangeNameBecauseDublicated', 'true');
			// 	return true;
			// }

			return false;
	}

	public function prevented($request)
	{
			// here just set message
			// in middelware MustChangeNamePreventAccount we prevent to test any exam

			// $prevented = [83595,35671,58110,58067,57923,98793,33887,137864,11022,112164,112091,136702,57993,99076,132872];
			//
			//
			// if(in_array(auth()->id(), $prevented)){
			// 		$request->session()->flash('userMustChangeNamePreventAccount', 'true');
			// }
	}
}
