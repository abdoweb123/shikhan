<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use App\libraries\_commonfn;
use App\libraries\Helpers;
use App\category;
use App\language;
use App\LessonOld;

use App\post;
use App\post_description;
use App\MemberPost;
use App;
use Auth;
use Session;
use Artisan;
class artisanController extends Controller
{
    public function clear_cache()
    {
        Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:cache');
            Artisan::call('clear-compiled');
            return "Cache is cleared";
    }



    public function storage()
    {
            Artisan::call('storage:link');
            return "storage:link";
    }


}
