<?php

namespace App\Http\Middleware;
use Closure;
use DB;

class MustChangeNamePreventAccount
{

	public function handle($request, Closure $next)
	{

			
			// if(auth()->check()){
			// 		if($this->prevented($request)){
			// 			return redirect()->route('home');
			// 		}
			//
			// 		if (auth()->id() != 5972 && auth()->id() != 5668 && auth()->id() != 5672 && auth()->id() != 43935 && auth()->id() != 59999){
			// 			if (
			// 				auth()->user()->email != 'mohamedabdallh2023@gmail.com' && auth()->user()->email != 'khalidalshoura0@gmail.com' &&
			// 				auth()->user()->email != 'yo775991525@gmail.com' && auth()->user()->email != 'mofidasknder164@gmail.com' &&
			// 				auth()->user()->email != 'mofidasknder164@gmail.com' && auth()->user()->email != 'yo775991525@gmail.com' &&
			// 				auth()->user()->email != 'swailimty@gmail.com' && auth()->user()->email != 'abbasugd6@gmail.com' &&
			// 				auth()->user()->email != 'swailimty@gmail.com' && auth()->user()->email != 'sabualrashta1@gmail.com' &&
			// 				auth()->user()->email != 'mofidasknder164@gmail.com' && auth()->user()->email != 'aalhopishi33@gmail.com' &&
			// 				auth()->user()->email != 'sfjn218@gmail.com' && auth()->user()->email != 'monif200@gmail.com' &&
			// 				auth()->user()->email != 'benalikarim50@gmail.com' && auth()->user()->email != 'alaagomaa1001@gmail.com' &&
			// 				auth()->user()->email != 'shussainbajaur164@gmail.com' && auth()->user()->email != 'abdullah2hmed@gmail.com' &&
			// 				auth()->user()->email != 'Tujimussa07@gmail.com' && auth()->user()->email != 'amhmw1097@gmail.com' &&
			// 				auth()->user()->email != 'ayadsm91@gmail.com'
			// 			){
			// 				$degrees = DB::Table('all_results_max')->where('user_id', auth()->id())->get();
			//
			// 				foreach($degrees as $degree){
			// 					$percToShowMessage = ($degree->count_succeessd_tests*100) * 98 /100; // 98%
			// 					if ($degree->site_total_degree >= $percToShowMessage){
			// 							$request->session()->flash('userGet100', 'true');
			// 					}
			// 				}
			// 			}
			// 		}
			// }

			return $next($request);

	}

	public function prevented($request)
	{
			$prevented =  [83595,35671,58110,58067,57923,98793,33887,137864,112164,112091,136702,57993,99076,132872];

			if(in_array(auth()->id(), $prevented)){
					return true;
			}
			return false;

	}
}
