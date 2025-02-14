<?php

namespace App\Http\Controllers\front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
use App\member;
use App\EmailVerification;
use Auth;
use Mail;

class VerifyController extends Controller
{

  public function showVerificationEmail(Request $request)
  {
      if (auth()->user()->email_verified_at){
          return redirect()->route('home');
      }


      $request->session()->forget('verify_sent_message');
      $request->session()->forget('verify_error_message');
      $request->session()->forget('google_form_message');


      $emailVerification = EmailVerification::where('user_id', auth()->id())->first();

      $token = '';
      if ($emailVerification){
          $token = $emailVerification->token;
      } else {
          do {
              $token = Str::random(64);
              $tokenExists = EmailVerification::where('token', $token)->exists();
          } while ( $tokenExists == true );

          EmailVerification::create([
            'token' => $token,
            'user_id' => Auth::guard('web')->id(),
          ]);
      }

      $settings = [
        'verification_link' => route('verify_verification_email', ['lang' => 'ar', 'token' => $token]),
        'email_to' => Auth::guard('web')->user()->email, // 'alaaffadaa@gmail.com', // 'tariksalahnet@hotmail.com'
      ];


      $googleFormMessage = '- إذا حاولت وواجهتك مشكلة انقر هذا الرابط وسيتم خدمتكم خلال يومين عمل إن شاء الله '.'<br>';
      $googleFormMessage = $googleFormMessage . '<a href="https://forms.gle/R2EwLB6H1UHe29Pp8" class="btn btn-info">أرسل مشكلتك</a>';
      $request->session()->put('google_form_message', $googleFormMessage);


      try {
          $email = new \App\Mail\VerificationEmail($settings);
          Mail::to($settings['email_to'])->send($email);

          $message = 'لاستلام شهادة الدبلوم لابد من تفعيل بريدكم'.'<br>';
          $message = $message . 'تم إرسال رسالة لبريدكم نرجوا النقر على الرابط الموجود فيها'.'<br>';
          $message = $message . 'تنبيه:'.'<br>';
          $message = $message . 'وصول الرسالة في البريد الوارد (inbox) أو في البريد المزعج أو الغير مرغوب فيه (spam) أو في البريد غير الهام(jankmail).'.'<br>';

          $request->session()->put('verify_sent_message', $message);
      } catch (\Exception $e) {
          $message = 'يبدوا أن بريدكم المسجل خطأ:'.'<br>';
          $message = $message . 'ضع بريدك الصحيح لاستلام شهادة الدبلوم'.'<br>';

          $request->session()->put('verify_error_message', $message);
      }


      return view('front.content.auth.email-verification');

  }

  public function sendVerificationEmail(Request $request)
  {

        // $emailVerification = EmailVerification::where('user_id', auth()->id())->first();
        //
        // $token = '';
        // if ($emailVerification){
        //     $token = $emailVerification->token;
        // } else {
        //     do {
        //         $token = Str::random(64);
        //         $tokenExists = EmailVerification::where('token', $token)->exists();
        //     } while ( $tokenExists == true );
        //
        //     EmailVerification::create([
        //       'token' => $token,
        //       'user_id' => Auth::guard('web')->id(),
        //     ]);
        // }
        //
        // $settings = [
        //   'verification_link' => route('verify_verification_email', ['lang' => 'ar', 'token' => $token]),
        //   'email_to' => Auth::guard('web')->user()->email, // 'alaaffadaa@gmail.com', // 'tariksalahnet@hotmail.com'
        // ];
        //
        //
        // try {
        //     $email = new \App\Mail\VerificationEmail($settings);
        //     Mail::to($settings['email_to'])->send($email);
        // } catch (\Exception $e) {
        // 	  return redirect()->route('show_verification_email')->with('verify_error_message', 'برجاء تصحيح البريد الاكترونى من صفحة حسابكم الشخصى');
        // }
        //
        // return redirect()->route('show_verification_email')->with('verify_sent_message', 'تم ارسال رسالة التفعيل - برجاء التحقق من بريدكم الشخصى');

  }

  public function verifyVerificationEmail(Request $request)
  {

      $request->merge(['token' => \Route::input('token') ?? '']);

      $request->validate([
        'token' => 'required|string|max:64',
      ],[],[
        'token.required' => 'رمز التحقق غير موجود',
      ]);

      $emailVerification = EmailVerification::where('token', $request->token)->first();

      if (! $emailVerification){
        return redirect()->route('show_verification_email')->with('verify_error_message', 'رمز التحقق غير صحيح');
      }

      member::where('id', $emailVerification->user_id)->update(['email_verified_at' => now()]);

      $emailVerification->delete();

      return redirect()->route('sites_certificates')->with('message', 'تم تفعيل البريد الاكترونى بنجاح  يمكنك الآن تحميل شهاداتك');;


  }

}
