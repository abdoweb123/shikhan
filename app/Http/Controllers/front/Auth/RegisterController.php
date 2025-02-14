<?php

namespace App\Http\Controllers\front\Auth;

use Illuminate\Http\Request;
use App\member;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;
use Auth;
use Session;
use Illuminate\Validation\ValidationException;
use Socialite;
use Illuminate\Support\Str;
use Mail;
use App\libraries\Helpers;
use App\Services\PartnerCodeService;
use App\Services\CountryService;

class RegisterController extends Controller
{

    use RegistersUsers;

    protected $redirectTo;

    public function __construct(private PartnerCodeService $partnerCodeService, private CountryService $countryService)
    {
        $this->redirectTo = route('home'); // route('diplomas.index'); // route('home');
        $this->middleware('guest:web');
    }

    protected function validator(array $data)
    {

        // $data = $this->mergeDiploms($data);


        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:50', 'min:'.config('project.max_user_name_chr')],
//            'name_lang' => ['nullable', 'string', 'max:50'],
//            'name_search' => ['string', 'max:50'], // , 'unique:members,name_search'
            'email' => ['nullable','string','email','unique:members'],
//            'phone' => 'required|numeric|unique:members',
//            // 'whats_app' => 'required|numeric|unique:members',
//            'country_id' => 'nullable|integer|exists:countries,id',
//            'country_name' => 'nullable|string|max:100',
//            'phone_code_id' => 'required|integer|exists:countries,id',
//            // 'city' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
//            'avatar' => 'file|image|mimes:jpg,png,jpeg|max:200',
//
//            'gender' => 'nullable|in:0,1,2',
//            'join_in'=>'nullable|string',
//            'diplome_ids'=> 'array',
//            'diplome_ids.*' => 'exists:sites,id',
//            // 'code' => 'nullable|string|max:10',
//            'amount' => 'nullable|numeric', // |min:1
//            'pay_image' => 'nullable|file|image|mimes:jpg,png,jpeg|max:1024',
        ],
          $messages = [
              'password' => __('messages.password'),
            'diplome_ids.required' => __('trans.must_select_diploma'),
            'name_search.unique' =>  __('messages.already_exists', ['var' => '']),
            'amount' =>  __('messages.amount_must_be'),
            'pay_image' =>  __('messages.pay_image_ruls'),
        ]);



    }

    protected function create(array $data)
    {

        // $data = $this->mergeDiploms($data);

//        $name_search = $this->validateName($data['name']);
//        $data = array_merge($data, ['name_search' => $name_search]);


        // if ($data['code']){
        //     $this->validateCodeExistsActive($data['code']);
        //
        //     $this->validateCodeUsed($data['code']);
        // }

        // if ($data['country_id'] === null && trim($data['country_name']) == ''){
        //     throw \Illuminate\Validation\ValidationException::withMessages(['country_id' => __('trans.select_or_write_country')]);
        // }

//        $this->validatePayImageIfAmountExists($data['amount'], isset($data['pay_image']) ? $data['pay_image'] : null);


        $globalService = new \App\Services\GlobalService();

        $member = new member();
//        $member->name = $data['name'];
        $member->name_lang = ''; // $data['name_lang'];
//        $member->name_search = $data['name_search'];
        $member->user_locale = app()->getLocale();
        $member->email = $data['email'];
        // $member->phone = $data['phone'];
//        $member->whats_app = $data['phone'];
//        $member->country_id = $data['country_id']; // will set in model
//        $member->phone_code_id = $data['phone_code_id'];
        // $member->country_name = $data['country_name'];
        // $member->city = $data['city'];
        $member->join_in = ''; // $data['join_in'];
        $member->gender = 0; // $data['gender'];
        $member->provider = 0;
        $member->password = Hash::make($data['password']);
        $member->avatar = (!empty($data['avatar'])) ? str_ireplace('members/','',$data['avatar']->store('members/avatar')) : '' ;
        $member->ip = request()->ip();

        // $member->free_status = member::FREE;
        // if ($data['code']){
        //   $member->partner_code = $data['code'];
        //   $member->free_status = member::SUSPEND;
        // }


        $member->pay_amount = $data['amount'];
        $member->free_status = $data['amount'] ? member::PAID_SUSPENDED : member::NOT_PAID;

        $member->save();
        $member = $member;


//        if (isset($data['pay_image'])){
//          $member->pay_image = $data['pay_image']->storeAs('pay_images', $member->id . '_' . $data['pay_image']->hashName() . '.' . $data['pay_image']->extension());
//          $member->free_status = member::PAID_SUSPENDED;
//          $member->save();
//        }





        // if user clcik ( subscripe diploma from outside ) so subscripe user in all courses of this diploma
//        $globalMessage = 'Welcome';
//
//        if(app()->getlocale() == 'sw'){
//          $globalMessage = '<span style="font-size: 20px;color: green;font-weight: bold;display: block;">' . __('trans.register_done') . '</span>' . '<br>'.
//            '<span style="display: block; font-size: 16px; padding-top: 10px; color: red;text-align: left;">' .
//            '<a style="color: #0c40b3;" href="https://t.me/+uHCc8IyQ83liYjg8">✅-Telegram:</a>'.'<br>'.
//            '<a style="color: #0c40b3;" href="https://chat.whatsapp.com/KbdH2kZejbu2mhbnP3Bfvq">✅- Whats up:</a>'.'<br>'.
//            '<a style="color: #0c40b3;" href="https://www.facebook.com/groups/875934823479901">✅- Facebook:</a>'.'<br>'.
//            '<a style="color: #0c40b3;" href="https://t.me/+F-6oApevPYEzZWM0">✅-Usaidizi wa kiufundi: </a>'.'<br>'
//            // '<span style="display: block; font-size: 16px; padding-top: 10px; color: red;">Ili ufanye malipo ya masomo ambayo ni machache au upate udhamini kwa yule ambaye hana uwezo. Bofya linki hii</span>'.
//            // '<a style="color: #0c40b3;" href="' . route('profile') .'#pay_div">linki</a>'.'</span>'.'<br>'
//            ;
//        }





//        Session::flash('welcome_message', $globalMessage);
//
//        if(empty($data['diplome_ids'])){
//          $sites = \App\site::select('id')
//            ->where('status',1)
//            ->whereNull('deleted_at')
//            ->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )
//            ->where('parent_id','!=',0)
//            ->get();
//
//          $member->sites()->syncWithoutDetaching( $sites ); // sites
//        } else {
//          $member->sites()->syncWithoutDetaching( $data['diplome_ids'] ); // sites
//        }




//        try {
//            // 0- store where the user came from
//            Helpers::storePreviousUrl('members', $member->id);
//
//            $subscribedCourses = $this->getSubscribedCourses($data['diplome_ids']);
//            $coursesHtmlPart = $this->getCoursesHtmlPart($subscribedCourses);
//
//            // store count subscribtions of current site of current date
//            $subscriptionService = new \App\Services\SubscriptionService();
//            foreach ($data['diplome_ids'] as $site_id) {
//              $subscriptionService->whereSiteId($site_id)->store();
//            }
//
//            // $firstSubscribedCourse = $subscribedCourses->first();
//            // if ($firstSubscribedCourse){
//            //   $this->redirectTo = route('courses.show',['site' => $firstSubscribedCourse->site_alias ,'course' => $firstSubscribedCourse->course_alias ]);
//            // }
//
//
//            // 1- mail
//            // $settings = [
//            //   'email_to' => $data['email'],
//            //   'extra_data' => $coursesHtmlPart
//            // ];
//            // $email = new \App\Mail\AfterRegistration($settings);
//            // // Mail::to($settings['email_to'])->send($email);
//            //
//            //
//            // // 2- notification
//            // $messageAfterRegestration = $this->getMessageAfterRegestration();
//            // $notificationService = new \App\Services\SendNotificationsInnerService();
//            // $notificationService->sendAfterRegisterNotification(
//            //     $member->id,[
//            //       'title' => 'مبارك التسجيل و القبول',
//            //       'message' => $messageAfterRegestration . ' ' . $coursesHtmlPart,
//            //     ]
//            //  );
//
//        } catch (\Exception $ex) {
//              DB::table('members')->where('email', $data['email'])->update([
//                'error_email' => $ex->getMessage()
//              ]);
//              \Illuminate\Support\Facades\Log::emergency($ex->getMessage());
//        }

        session()->forget('register_every_page');



        return $member;

    }

    private function getMessageAfterRegestration()
    {
      $settingService = new \App\Services\SettingService();
      return $settingService->getMessageAfterRegestration();
    }

    private function getSubscribedCourses($ids = [])
    {
        return DB::Table('courses')
          ->join('course_site','courses.id','course_site.course_id')
          ->join('courses_translations','courses.id','courses_translations.course_id')
          // ->join('sites_translations','sites_translations.site_id','course_site.site_id')
          ->join('sites','sites.id','course_site.site_id')
          ->wherein('course_site.site_id', $ids)
          ->where('courses.status',1)
          ->whereNull('courses.deleted_at')
          ->select('courses_translations.name','courses_translations.alias as course_alias','sites.alias as site_alias')
          ->orderBy('courses_translations.date_at')
          ->get();
    }

    private function getCoursesHtmlPart($courses)
    {
        $style = "padding: 3px;text-decoration: underline;font-size: 18px;";
        $aStyle = "color: #a67241;font-weight: bold;";
        $html = '<div>';
        foreach ($courses as $course) {
          $route = route('courses.show',['site' => $course->site_alias ,'course' => $course->course_alias ]);
          $html = $html . "<div style='" . $style . "'><a style='" . $aStyle . "' href='" . $route . "' title='" . $course->name . " ' class='d-block'>" . $course->name . "</a></div>";
        }
        return $html . '</div>';
    }

    public function showRegistrationForm()
    {

      // Session::flush();
      // ini_set('memory_limit', '512M');
        $data = [
            'page_name' => __('meta.title.register'),
            'page_key' => 'register',
            'body_id' => 'register',
        ];

        $data['sites'] = \App\site::select('id','title','logo','new_flag','conditions')
          ->where('status',1)
          ->whereNull('deleted_at')
          ->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )
          ->where('parent_id','!=',0)
          ->get();


        $countries = $this->countryService->filter();
        $data['countries'] = $countries->sortBy('name');

        $allowedCountries = $this->countryService->getAllowedCountries();
        $data['allowedCountries'] = $allowedCountries->sortBy('name');

        set_meta('register');
        //$this->seoInfo('regitser','');

        $data['register_every_page'] = ['name' => '', 'phone' => '', 'email' => ''];
        if(session()->has('register_every_page')){
          $data['register_every_page'] = session('register_every_page')[0];
        }


        Helpers::storePreviousUrl('register', '0');
        session()->put('came_from_url', url()->previous());

        return view('front.content.auth.register', $data);

    }

    public function guard()
    {
        return Auth::guard('web');
    }

    public function mergeDiploms(array $data)
    {
        $data['diplome_ids'] = [];
        if (isset($data['diplome_ids_1'])){
          foreach ($data['diplome_ids_1'] as $value) {
            array_push($data['diplome_ids'], $value);
          }
        }

        if (isset($data['diplome_ids_2'])){
          foreach ($data['diplome_ids_2'] as $value) {
            array_push($data['diplome_ids'], $value);
          }
        }

        return $data;
    }


    public function validateNameAjax(Request $request)
    {
        $request->validate([
          'name'=>'required|string|max:50',
        ]);

         return $this->validateName($request->name);
    }

    public function validateName($name)
    {

         $name_search = \App\helpers\UtilHelper::formatNormal($name);

         if(DB::Table('members')->where('name_search', $name_search )->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['name' => 'الاسم مكرر']);
         }

         return $name_search;

    }


    public function registerEveryPage(Request $request)
    {
        session()->push('register_every_page', [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => '',
        ]);

        return redirect()->route('register'); // ->route('register', ['lang' => app()->getLocale()]);
    }


    // private function validateCodeExistsActive($code)
    // {
    //     if (! $this->partnerCodeService->isCodeExistsActive($code)){
    //         throw ValidationException::withMessages(['code' => __('messages.invalid_code') ]);
    //     }
    // }
    //
    //
    // private function validateCodeUsed($code)
    // {
    //     if ($this->partnerCodeService->isCodeUsed($code)){
    //         throw ValidationException::withMessages(['code' => __('messages.invalid_code') ]);
    //     }
    // }


    private function validatePayImageIfAmountExists($amount, $payImage)
    {
        if (! $amount){
          return;
        }

        if ($amount && !$payImage){
          throw ValidationException::withMessages(['pay_image' => __('messages.enter_pay_image') ]);
        }
    }




    // social --------------------------
    public function redirectToProvider(Request $request)
    {
        $driver = \Route::input('driver');
        if (in_array($driver,['facebook','twitter','google'])) {
            return Socialite::driver($driver)->redirect();
        } else {
            abort(404);
        }
    }

    public function handleProviderCallback(Request $request)
    {


    }


}
