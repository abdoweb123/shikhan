<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\site;
use App\course;
use App\member;
use App\language;
use Illuminate\Support\Facades\Input;
use Validator;
use PDF;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;


class subscribers extends Controller
{
    public function index()
    {
        $data['site_alias'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);
        $data['result'] = $data['course']->subscribers()->orderBy('created_at', 'ASC')->paginate(500);
        // $data['counts']['total'] = $data['course']->test_results()->count();
        // $data['counts']['not_send'] = $data['course']->test_results()->where('flag',0)->count();
        // $data['counts']['error_send'] = $data['course']->test_results()->where('flag',1)->count();
        // $data['counts']['sended'] = $data['course']->test_results()->where('flag',2)->count();
        // $data['counts']['not_lucky'] = $data['course']->test_results()->where('rate',0)->count();
        // $data['counts']['lucky'] = $data['course']->test_results()->where('rate','!=',0)->count();

        return view ('back.content.subscribers.index',$data);
    }

    public function create()
    {
        $data['site_alias'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);

        return view ('back.content.subscribers.create',$data);
    }

    public function store(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
        ]);
        $user = member::where('email',$request->email);
        if ($user->count())
        {
            $user = $user->first();

            if ($user->courses()->find($course_id))
            {
                $validator->after(function ($validator) {
                    $validator->errors()->add('error', 'you are subscribed already!');
                });
            }
        }
        elseif (!$validator->fails())
        {
            $user = member::create(
            [
                'email' => $request->email,
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => bcrypt('123456'),
                'created_by' => Auth::guard('admin')->user()->id,
                'avatar' => member::default_avatar(),
                'status' => 1,
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user->courses()->attach($course_id);
        return redirect()->route('dashboard.subscribers.create',['site' => $site_alias,'course' => $course_id])->with('success', 'Member Added Successfully!');
    }

    public function show($id)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('subscriber');
    }

    public function destroy(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('subscriber');
        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $row = $course->subscribers()->detach($id);

        return redirect()->route('dashboard.subscribers.index',['site' => $site_alias,'course' => $course_id])->with('success', 'Member deleted Successfully!');
    }
}
