<?php

namespace App\Http\Controllers\core;
use Session as Session ;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Auth as Auth;
// use App\libraries\_commonfn;

class back_controller extends Controller
{
    public function __construct()
    {
		config(['laravellocalization.hideDefaultLocaleInURL' => false]);
        $this->middleware('auth:admin');
    }
}
