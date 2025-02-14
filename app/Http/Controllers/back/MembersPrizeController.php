<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

use App\Exports\membersExport;

use App\member;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class MembersPrizeController extends Controller
{
    public function index(Request $request)
    {

        ini_set('memory_limit', -1);
        $data['get'] = $request->input();
        $data['result'] = member::wherehas('prizes')->withCount('prizesDatas','courses')->having('prizes_datas_count', '>', 0)->orderBy('prizes_datas_count', 'desc');

        if (!empty($data['get']['term'])) {$data['result']->where('email','like','%'.$data['get']['term'].'%');}
        $data['result'] = $data['result']->paginate(300);
  // dd($data['result']);
        return view ('back.content.prize.index',$data);


        // $data['get'] = $request->input();
        //
        // $result = collect();
        // $memberCourses = DB::table('members')
        //   ->join('course_subscriptions','members.id','course_subscriptions.user_id')
        //   ->join('courses','course_subscriptions.course_id','courses.id')
        //   ->select('members.id','members.ps','members.name','members.email','members.phone','members.status','members.created_at',
        //           'courses.title')->orderBy('members.id')->chunk(30, function($members) use($result) {
        //             $result->merge($members);
        //   });
        //
        //   dd($result);

    }


    public function create()
    {
        return view ('back.content.members.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','email',Rule::unique('members')],
            'password' => 'confirmed|string|min:6|required',
            'avatar' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'name' => 'required',
            'birthday' => 'nullable|date',
            'phone' => 'nullable|string|min:11',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $avatar = '';
        if (!empty($request->avatar))
        {
            $avatar = $request->avatar->store('members');
        }

        $save = member::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone',null),
            'birthday' => $request->input('birthday',null),
            'gender' => $request->input('gender'),
            'password' => bcrypt($request->input('password','123456')),
            'created_by' => Auth::guard('admin')->user()->id,
            'avatar' => $avatar,
            'provider' => 0,
            'status' => 0,
        ]);

        return redirect()->route('dashboard.members.create')->with('success', 'Member Added Successfully!');
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request)
    {
        $id = \Route::input('member');
        $data['fields'] = member::where(['id' => $id])->firstOrFail();
        return view ('back.content.members.edit',$data);
    }

    public function update(Request $request)
    {
        dd('ssssssssss');

        $id = \Route::input('member');
        $validator = Validator::make($request->all(), [
            'email' => ['required','email',Rule::unique('members')->ignore($id)],
            'password' => 'confirmed'.((is_numeric($id))?'':'|string|min:6|required'),
            'avatar' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'name' => 'required',
            'birthday' => 'nullable|date',
            'phone' => 'nullable|string|min:11',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $save = member::where(['id' => $id])->firstOrFail();

        $save->name = $request->input('name');
        $save->email = $request->input('email');
        $save->phone = $request->input('phone',null);
        $save->birthday = $request->input('birthday',null);
        $save->gender = $request->input('gender');

        if (!empty($request->input('password')))
        {
            $save->password = bcrypt($request->input('password'));
            $save->ps = '';
        }

        if (!empty($request->avatar))
        {
            if (!empty($save->avatar))Storage::delete(public_path('/'.$save->avatar));
            $save->avatar = $request->avatar->store('members');
        }

        $save->updated_by = Auth::guard('admin')->user()->id;
        $save->save();
        // redirect
        return redirect()->route('dashboard.members.index')->with('success', 'Member updated Successfully!');
    }

    public function status(Request $request)
    {
        $id = \Route::input('member');
        $status = $request->input('status');

        $save = member::findOrFail($id);
        $save->status = intval($status);
        $save->updated_by = Auth::guard('admin')->user()->id;
        $save->save();

        return redirect()->route('dashboard.members.index')->with('success', $status ? 'Member Enabled Successfully!' : 'Member Disabled Successfully!');
    }

    public function destroy(Request $request)
    {
        $id = \Route::input('member');

        $row = member::where(['status' => 0,'id' => $id])->firstOrFail();
        $row->delete();

        return redirect()->route('dashboard.members.index')->with('success', 'Member deleted Successfully!');
    }

    public function createUserPassword(Request $request)
    {
        $member = member::findOrFail($request->user_id);
        $randomePassword = generateRandomString(8);
        $member->ps = $randomePassword;
        $member->password = bcrypt($randomePassword);
        $member->save();
        return back();
    }

    public function createUsersPasswords()
    {

        member::where('admin','!=' , 1)->orderBy('id')->chunk(10, function ($members) {
              foreach ($members as $member) {
                $randomePassword = generateRandomString(8);
                $member->ps = $randomePassword;
                $member->password = bcrypt($randomePassword);
                $member->save();
              }
        });

        return redirect()->route('dashboard.members.index');
    }

    public function sendUsersPasswords()
    {

        app()->setLocale('ar');
        member::where('admin','!=' , 1)->wherein('id',[2,4836])->orderBy('id')->chunk(5, function ($members) {
              foreach ($members as $row) {

                $send = 0;
                $return = $this->send_prossess($row);
                if($return['status'] == 'success'){$send++;}

                if ($send > 0){
                      $flash_data = ['status' => 'success','message' => $send.' passwords have been sent to members'];
                } else {
                      $flash_data = ['status' => 'error','message' => 'A problem occurred while sending the passwords!'];
                }

            }
        });

        app()->setLocale('en');
        return redirect()->route('dashboard.members.index');

    }


    private function send_prossess($row)
    {

        \Mail::send('emails.send_password', ['data' => $row],
        function ($mail) use ($row)
        {
            $mail
            ->from(config('mail.from.address'),config('mail.from.name'))
            ->to($row->email,$row->name)->subject( __('core.send_user_password_subject') );
        });

        if(empty(\Mail::failures())) {
            $return = ['status' => 'success','message' => 'Passwords has been sended Successfully!'];
        } else {
            $return = ['status' => 'error','message' => 'A problem occurred while sending the passwords!'];
            // dd(\Mail::failures());
        }
        return $return;
    }

    public function export_(Request $request)
    {
       $members = member::get();
       // dd($members);
      $type ='xlsx';

        $return = (new membersExport());
        $extensions = config('excel.extension_detector');

        if(in_array($type,array_keys($extensions)))
        {
            return $return->download('membersExport.'.$type,$extensions[$type]);
        }
        else
        {
            $return->download('membersExport.csv',\Maatwebsite\Excel\Excel::CSV);
        }
    }



}
