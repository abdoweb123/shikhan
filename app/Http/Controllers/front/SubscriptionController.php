<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use Session;
use App\Services\GlobalService;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Log;
use Validator;

class SubscriptionController extends Controller
{

    private $globalService;
    private $subscriptionService;

    public function __construct(GlobalService $globalService, SubscriptionService $subscriptionService)
    {
        $this->globalService = $globalService;
        $this->subscriptionService = $subscriptionService;
    }

    public function subscribe(Request $request,$alise_site)
    {

        $validator = Validator::make(['alise_site' => $alise_site], [
            'alise_site' => 'required|max:250|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


         $user = Auth::guard('web')->user();
         if (! $user){
             if($request->ajax()){
               return response()->json(['status'=>false]);
             }

             return redirect( route('login'));
         }

         if( $alise_site == 'all'){
             // get all corses from courses table then sync
             $courses = \App\course::pluck('id');
             $user->courses()->syncWithoutDetaching($courses);



             $sites = \App\site::select('id')->get();
             $user->sites()->syncWithoutDetaching($sites->pluck('id'));
             // change_sta
             foreach ($sites as $site) {
               // $this->staService->incremantSiteSubsUsersCount($site->id);
               // $this->staService->incremantSiteSubsCount($site->id);
             }

             Session::flash('success', 'تم الاشتراك');
             return redirect()->back();
        }


        $site = \App\site::where('status', 1)->whereTranslation('slug', $alise_site)->select('sites.id')->firstOrFail();
        $user->sites()->syncWithoutDetaching( [$site->id] );


        $this->storeMemberSiteSubscriptions($site->id);



        if($request->ajax()){
          return response()->json(['status'=>true]);
        }

        Session::flash('success', 'تم الاشتراك');
        return redirect()->back();

    }

    public function subscripeInSitesFromOutside(Request $request)
    {

        Session::forget('siteIdTosubscripe');

        $siteAlias = \Route::input('site_alias');

        $validator = Validator::make(['siteAlias' => $siteAlias], [
            'siteAlias' => 'required|max:250|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }



        $site = null;
        if ( $siteAlias ){
          $site = \App\site::where(['status' => 1,'alias' => $siteAlias])->select('id')->firstorfail();
          $sites = [$site->id];
        } else {
          $sites = \App\site::where(['status' => 1])->pluck('id');
        }

        if (Auth::Check()){
          $user = Auth::guard('web')->user();
          $this->globalService->subscripeUserInManySites($user, $sites);

          $user->sites()->syncWithoutDetaching( $sites );


          $this->storeMemberSiteSubscriptions($site->id);


          Session::flash('global_message', 'تم اكتمال الاشتراك بنجاح' );
          if ($site) {
            return redirect( route('courses.index',$siteAlias ));
          } else {
            return redirect( route('diplomas.index'));
          }
        } else {
          if ($site) {
            Session::put('siteIdTosubscripe', $site->id );
          }
          return redirect( route('register') );
        }

    }

    public function unSubscribe(Request $request,$alise_site)
    {

        $validator = Validator::make(['alise_site' => $alise_site], [
            'alise_site' => 'required|max:250|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


         $user = Auth::guard('web')->user();
         if (! $user){
             if($request->ajax()){
               return response()->json(['status'=>false]);
             }

             return redirect( route('login'));
         }

        $site = \App\site::where('status', 1)->whereTranslation('slug', $alise_site)->select('sites.id')->firstOrFail();
        $user->sites()->detach( [$site->id] );


        $this->storeMemberSiteSubscriptions($site->id);


        if($request->ajax()){
          return response()->json(['status'=>true]);
        }

        Session::flash('success', 'تم الغاء الإشتراك');
        return redirect()->back();

    }

    public function storeMemberSiteSubscriptions($siteId)
    {
        $this->subscriptionService->whereSiteId($siteId)->store();
    }



}
