<?php


namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\site;
use App\course;
use App\language;
use Illuminate\Support\Facades\Input;
use Validator;
use PDF;
use Anam\PhantomMagick\Converter;
use Illuminate\Support\Facades\Cache;



class sender extends Controller
{
    public function index()
    {
        $data['site_alias'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);
        $data['result'] = $data['course']->corn_jobs()->orderBy('id', 'ASC')->paginate(15);

        if (!Cache::has('languages-'.$data['site_alias']))
        {
            Cache::forever('languages-'.$data['site_alias'] , language::on($data['site_alias'])->where('status',1)->pluck('name','alies')->toArray());
        }
        $data['languages'] = [];
        foreach (Cache::get('languages-'.$data['site_alias']) as $alias => $name)
        {
            if (in_array($alias,$data['course']->languages))
            {
                $data['languages'][$alias] = $name;
            }
        }

        return view ('back.content.sender.index',$data);
    }

    public function create()
    {
        $data['site_alias'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);

        if (!Cache::has('languages-'.$data['site_alias']))
        {
            Cache::forever('languages-'.$data['site_alias'] , language::on($data['site_alias'])->where('status',1)->pluck('name','alies')->toArray());
        }
        $data['languages'] = [];
        foreach (Cache::get('languages-'.$data['site_alias']) as $alias => $name)
        {
            if (in_array($alias,$data['course']->languages))
            {
                $data['languages'][$alias] = $name;
            }
        }
        return view ('back.content.sender.create',$data);
    }

    public function store(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $validator = Validator::make($request->all(), [
            'frequency' => 'required|string|max:255',
            'count' => 'required|numeric|between:0,100',
            'languages' => 'required|array',
            'languages.*' => 'min:2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $site = site::where('alias',$site_alias)->firstOrFail();
        $save = $site->courses()->findOrFail($course_id)->corn_jobs()->create([
            'frequency' => $request->input('frequency'),
            'count' => $request->input('count'),
            'languages' => $request->input('languages'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);

        return redirect()->route('dashboard.sender.create',['site' => $site_alias,'course' => $course_id])->with('success', 'Job Added Successfully!');
    }

    public function show($id)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('sender');
    }

    public function edit(Request $request)
    {
        $data['site_alias'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $id = \Route::input('sender');
        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);
        $data['fields'] = $data['course']->corn_jobs()->findOrFail($id);

        if (!Cache::has('languages-'.$data['site_alias']))
        {
            Cache::forever('languages-'.$data['site_alias'] , language::on($data['site_alias'])->where('status',1)->pluck('name','alies')->toArray());
        }
        $data['languages'] = [];
        foreach (Cache::get('languages-'.$data['site_alias']) as $alias => $name)
        {
            if (in_array($alias,$data['course']->languages))
            {
                $data['languages'][$alias] = $name;
            }
        }
        return view ('back.content.sender.edit',$data);
    }

    public function update(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('sender');
        $validator = Validator::make($request->all(), [
            'frequency' => 'required|string|max:255',
            'count' => 'required|numeric|between:0,100',
            'languages' => 'required|array',
            'languages.*' => 'min:2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $save = $course->corn_jobs()->findOrFail($id);
        $save->frequency = $request->input('frequency');
        $save->count = $request->input('count');
        $save->languages = $request->input('languages');
        $save->updated_by = Auth::guard('admin')->user()->id;
        $save->save();
        // redirect
        return redirect()->route('dashboard.sender.index',['site' => $site_alias,'course' => $course_id])->with('success', 'Job updated Successfully!');
    }

    public function status(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('sender');
        $status = $request->input('status');

        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $save = $course->corn_jobs()->findOrFail($id);
        $save->status = intval($status);
        $save->updated_by = Auth::guard('admin')->user()->id;
        $save->save();

        return redirect()->route('dashboard.sender.index',['site' => $site_alias,'course' => $course_id])->with('success', $status ? 'Job Enabled Successfully!' : 'Job Disabled Successfully!');
    }

    public function destroy(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('sender');

        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $row = $course->corn_jobs()->findOrFail($id);
        $row->delete();

        return redirect()->route('dashboard.sender.index',['site' => $site_alias,'course' => $course_id])->with('success', 'Job deleted Successfully!');
    }
}
