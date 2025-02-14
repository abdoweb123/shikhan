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




class PrevUrl extends Controller
{

    public function index(Request $request)
    {

        $data = PrevUrl::get();
        return view('back.content.PrevUrl.index',compact(['data']));
    }



}
