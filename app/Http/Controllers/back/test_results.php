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
use App\Exports\TestResults as Test_resultsExport;
use App\Imports\TestResults as TestResultsImport;
use Validator;
use PDF;
use Excel;
use Anam\PhantomMagick\Converter;
// use Anam\PhantomLinux\Path;
use Illuminate\Support\Facades\Cache;



class test_results extends Controller
{
    public function index(Request $request)
    {
        $data['site_id'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $data['get'] = $request->input();
        $data['site'] = site::where('id',$data['site_id'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);
        $courseLanguages = $data['course']->translations()->pluck('locale');

        if (!Cache::has('languages-'.$data['site_id']))
        {
            Cache::forever('languages-'.$data['site_id'] , language::where('status',1)->pluck('name','alies')->toArray());
        }
        $data['languages'] = [];
        foreach (Cache::get('languages-'.$data['site_id']) as $alias => $name)
        {
            if ( in_array($alias, $courseLanguages->toArray()) )
            {
                $data['languages'][$alias] = $name;
            }
        }


        $data['counts']['total'] = $data['course']->test_results()->count();
        $data['counts']['not_send'] = $data['course']->test_results()->where('flag',0)->count();
        $data['counts']['error_send'] = $data['course']->test_results()->where('flag',1)->count();
        $data['counts']['sended'] = $data['course']->test_results()->where('flag',2)->count();
        $data['counts']['not_lucky'] = $data['course']->test_results()->where('rate',0)->count();
        $data['counts']['lucky'] = $data['course']->test_results()->where('rate','!=',0)->count();

        foreach ($data['languages'] as $alias => $name)
        {
            $data['counts']['locale'][$alias] = $data['course']->test_results()->where('locale',$alias)->count();
        }
        $data['result'] = $data['course']->test_results()->orderBy('id', 'ASC');

        if (!empty($data['get']['term'])) {$data['result']->whereHas('member', function ($query) use($data) {$query->where('email','like','%'.$data['get']['term'].'%');});}
        if (isset($data['get']['flag'])) {$data['result']->where('flag',$data['get']['flag']);}
        if (isset($data['get']['certificate'])) {$data['result']->where('rate',$data['get']['certificate'] ? '!=' : '=' ,0);}
        if (isset($data['get']['locale'])) {$data['result']->where('locale',$data['get']['locale']);}

        $data['result'] = $data['result']->paginate(30);

        return view ('back.content.test_results.index',$data);
    }

    public function send_all(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $get = $request->input();
        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);

        $result = $course->test_results()->orderBy('id', 'ASC');
        if (!empty($get['term'])) {$result->whereHas('member', function ($query) use($data) {$query->where('email','like','%'.$get['term'].'%');});}
        if (isset($get['flag'])) {$result->where('flag',$get['flag']);}
        if (isset($get['certificate'])) {$result->where('rate',$get['certificate'] ? '!=' : '=' ,0);}
        if (isset($get['locale'])) {$result->where('locale',$get['locale']);}

        $results = $result->paginate(30);
        $messages = [];foreach ($course->languages as $row){$messages[$row] = $course->translate($row);}

        $flash_data = ['status' => '','message' => ''];
        if($results->count())
        {
            $send = 0;
            foreach ($results as $row)
            {
                $message = $messages[$row->locale];
                $return = $this->send_prossess($course,$row,$message);
                if($return['status'] == 'success'){$send++;}
            }
            if ($send > 0)
            {
                $flash_data = ['status' => 'success','message' => $send.' certificates have been sent to members'];
            }
            else
            {
                $flash_data = ['status' => 'error','message' => 'A problem occurred while sending the certificate!'];
            }
        }
        else
        {
            $flash_data = ['status' => 'info','message' => 'There are no certificates to send'];
        }
        return redirect()->route('dashboard.test_results.index',['site' => $site_alias,'course' => $course_id])->with($flash_data['status'],$flash_data['message']);
    }

    public function create()
    {
        $data['site_id'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $data['site'] = site::where('id',$data['site_id'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);

        if (!Cache::has('languages-'.$data['site_alias']))
        {
            Cache::forever('languages-'.$data['site_alias'] , language::where('status',1)->pluck('name','alies')->toArray());
        }
        $data['languages'] = [];
        foreach (Cache::get('languages-'.$data['site_alias']) as $alias => $name)
        {
            if (in_array($alias,$data['course']->languages))
            {
                $data['languages'][$alias] = $name;
            }
        }

        return view ('back.content.test_results.create',$data);
    }

    public function store(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'email|required|max:255',
            'degree' => 'required|numeric|between:0,100',
            'phone' => 'numeric',
            'locale' => 'min:2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = member::where('email',$request->email);
        if ($user->count())
        {
            $user = $user->first();
        }
        else
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

        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $rate = $request->degree >= 90 && $request->degree <= 100 ? 5 : ($request->degree >= 80 && $request->degree <= 89 ? 4 : ($request->degree >= 70 && $request->degree <= 79 ? 3 : ($request->degree >= 60 && $request->degree <= 69 ? 2 : ($request->degree >= 50 && $request->degree <= 59 ? 1 : 0))));

        $save = $course->test_results()->create([
            'user_id' => $user->id,
            'degree' => $request->input('degree'),
            'rate' => $rate,
            'locale' => $request->input('locale'),
            'created_by' => Auth::guard('admin')->user()->id,
            'flag' => 0,
        ]);
        if ($request->submit == 'create_send' && $save->rate != '0')
        {
            $message = $course->translate($save->locale);
            $return = $this->send_prossess($course,$save,$message);
        }

        return redirect()->route('dashboard.test_results.create',['site' => $site_alias,'course' => $course_id])->with('success', 'Member Added Successfully!');
    }

    public function show($id)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('test_result');
    }

    public function edit(Request $request)
    {
        $data['site_alias'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $id = \Route::input('test_result');
        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);
        $data['fields'] = $data['course']->test_results()->findOrFail($id);

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

        return view ('back.content.test_results.edit',$data);
    }

    public function update(Request $request)
    {

        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('test_result');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'email|required|max:255',
            'phone' => 'nullable|numeric',

            'degree' => 'required|numeric|between:0,100',
            'locale' => 'min:2',
        ]);



        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $save = $course->test_results()->findOrFail($id);

        $member = member::where('email',$request->input('email'))->first();
        $member->email = $request->input('email');
        $member->name = $request->input('name');
        if ($request->input('phone')){ $member->phone = $request->input('phone'); }
        $member->save();


        $save->degree = $request->input('degree');
        $save->rate = $request->degree >= 95 && $request->degree <= 100 ? 4 : ($request->degree >= 86 && $request->degree <= 94 ? 3 : ($request->degree >= 80 && $request->degree <= 85 ? 2 : ($request->degree >= 70 && $request->degree <= 79 ? 1 : 0)));
        $save->locale = $request->input('locale');
        $save->updated_by = Auth::guard('admin')->user()->id;
        $save->save();

        if ($request->submit == 'edit_send' && $save->rate != '0')
        {
            $message = $course->translate($save->locale);
            $return = $this->send_prossess($save,$message);
        }
        // redirect
        return redirect()->route('dashboard.test_results.index',['site' => $site_alias,'course' => $course_id])->with('success', 'Member updated Successfully!');
    }

    public function export(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $type = \Route::input('type');
        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $return = (new Test_resultsExport($course->id));
        $extensions = config('excel.extension_detector');

        if(in_array($type,array_keys($extensions)))
        {
            return $return->download($course->name.'_test_results.'.$type,$extensions[$type]);
        }
        else
        {
            $return->download($course->name.'_test_results.csv',\Maatwebsite\Excel\Excel::CSV);
        }
    }

    public function import(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $validator = Validator::make($request->all(), [
            'import_file' => ['required','file','mimes:csv,xls,xlsx,txt,html'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        Excel::import(new TestResultsImport($site_alias,$course_id), $request->file('import_file'));
        return redirect()->route('dashboard.test_results.index',['site' => $site_alias,'course' => $course_id])->with('success', 'Members Results Added Successfully!');
    }

    public function send(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('test_result');
        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $row = $course->test_results()->where([['id','=',$id],['rate','!=',0]])->firstOrFail();
        $message = $course->translate($row->locale);
        $return = $this->send_prossess($course,$row,$message);

        return redirect()->route('dashboard.test_results.index',['site' => $site_alias,'course' => $course_id])->with($return['status'],$return['message']);
    }

    // send download pdf link with email (no attched file)
    private function send_prossess($course,$row,$message)
    {

        $row->update(['flag' => 1]);
        $content = view('emails.results', ['data' => $row,'subject' => $message->subject,'content' => $message->content]);
        $conv = new Converter();
        $options = [
          'format' => $course->format,
          'orientation' => $course->orientation,
          'margin' => '.1cm'
        ];
        $conv->setPdfOptions($options)->addPage($content)
        ->setBinary(base_path('vendor/anam/phantomjs-2.1.1-linux-x86_64/bin/phantomjs'))
        ->save(storage_path('app/public/public/certificates/'.$row->locale.'-'.$row->id.'.pdf'));


        app()->setLocale($row->locale);
        $pdfLink = route('download_certificate' , [ 'file' => $row->locale.'-'.$row->id.'.pdf' ]);
        $pdfLink = "<a href='$pdfLink' style='font-size:22px;'>".__('trans.download_certificate')."</a>";
        $message->content = $message->content . '  <br>  ' . $pdfLink;
        app()->setLocale('en');
        // dd($message->content);

        \Mail::send('emails.results', ['data' => $row,'subject' => $message->subject,'content' => $message->content],
        function ($mail) use ($row,$message)
        {
            $mail
            ->from(config('mail.from.address'),config('mail.from.name'))
            ->to($row->member->email,$row->member->name)->subject($message->subject);
        });

        if(empty(\Mail::failures()))
        {
            $row->update(['flag' => 2]);
            $return = ['status' => 'success','message' => 'Certificate has been sended Successfully!'];
        }
        else
        {
            $return = ['status' => 'error','message' => 'A problem occurred while sending the certificate!'];
            // dd(\Mail::failures());
        }
        return $return;
    }

    // send pdf attached with email
    private function send_prossess_pdf($course,$row,$message)
    {

        $row->update(['flag' => 1]);
        $content = view('emails.results', ['data' => $row,'subject' => $message->subject,'content' => $message->content]);
        $conv = new Converter();
        $options = [
          'format' => $course->format,
          'orientation' => $course->orientation,
          'margin' => '.1cm'
        ];
        $conv->setPdfOptions($options)->addPage($content)
        ->setBinary(base_path('vendor/anam/phantomjs-2.1.1-linux-x86_64/bin/phantomjs'))
        ->save(storage_path('app/public/public/certificates/'.$row->locale.'-'.$row->id.'.pdf'));


        \Mail::send('emails.results', ['data' => $row,'subject' => $message->subject,'content' => $message->content],
        function ($mail) use ($row,$message)
        {
            $mail
            ->from(config('mail.from.address'),config('mail.from.name'))
            ->to($row->member->email,$row->member->name)->subject($message->subject)
            ->attach(storage_path('app/public/public/certificates/'.$row->locale.'-'.$row->id.'.pdf'), [
                'mime' => 'application/pdf',
                'as' => 'certificate.pdf',
            ]);
        });

        if(empty(\Mail::failures()))
        {
            $row->update(['flag' => 2]);
            $return = ['status' => 'success','message' => 'Certificate has been sended Successfully!'];
        }
        else
        {
            $return = ['status' => 'error','message' => 'A problem occurred while sending the certificate!'];
            // dd(\Mail::failures());
        }
        return $return;
    }

    public function destroy(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('test_result');
        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $row = $course->test_results()->findOrFail($id);
        $row->delete();

        return redirect()->route('dashboard.test_results.index',['site' => $site_alias,'course' => $course_id])->with('success', 'Member deleted Successfully!');
    }



}
