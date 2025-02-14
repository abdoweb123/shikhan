<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\siteAdminRequest;


use App\site;
use App\course;
use App\prevUrl;
use DB;



class PrevUrlController extends Controller
{

    public function index(Request $request)
    {
        
        $data = PrevUrl::groupBy('groupBy','type','table_id')
          ->select('groupBy','type','table_id', DB::raw('count(*) as total'))->get();
        // return $data;
        // foreach ($data as $key => $value) {
        //   $groupBy='Other';
        //   if (str_contains($value->url, 'googleads')) {
        //     $groupBy='google';
        //   }elseif (str_contains($value->url, 'facebook')) {
        //     $groupBy='facebook';
        //   }
        //   $value->groupBy=$groupBy;
        //   $value->save();
        // }
        return view('back.content.PrevUrl.index',compact(['data']));
    }



}
