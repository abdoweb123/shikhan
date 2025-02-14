<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use App;
use App\course;
use App\site;
use App\course_site;
use Session;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use App\Services\SiteService;

class index extends Controller
{

    private $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    public function index()
    {
        if (auth('teacher')->check()) {
            return  view('back.content.home-partner');
        }

        if (Auth::guard('admin')->user()->type_id == 2){
          return  view('back.content.home-partner');
        }


        $data['courses'] = [];
        $data['result'] = [];
        return  view('back.content.home',$data);

    }

    public function delete(Request $request,$section,$field)
    {
        $response =
        [
            'status' => 'error',
            'msg' => __('message.error_not_found'),
        ];

        $status = 400 ;
        if ($request->isMethod('post'))
        {
            if (!empty($request->input('file')))
            {
                $status = 200 ;
                Storage::delete($section.'/'.$request->input('file'));
                $response =
                [
                    'status' => 'success',
                    'msg' => __('message.message_delete'),
                ];
            }
        }
        return response()->json($response,$status);
    }

    public function upload(Request $request,$section,$field)
    {
        $locale = \App::getLocale();

        ini_set('memory_limit','-1');

        $validation =
        [
            'images' => 'mimes:jpeg,bmp,png,webp|max:20000',
            'catalogs' => 'mimes:pdf,pptx,csv,xlsx,docx|max:20000',
        ];

        $data['request'] = $request;
        $data['file_name'] = '';

        if ($request->isMethod('post'))
        {
            $this->validate($request, [
                $field => $validation[$field],
            ]);

            if (!empty($request->{$field}))
            {
                $data['file_name'] = str_ireplace($section.'/','',$request->{$field}->store($section));
            }
        }
        $data['field'] = $field;
        $data['base_url'] = route('file.upload',['section' => $section,'field' => $field]);
        return view('back.files.iframe',$data);
    }
}
