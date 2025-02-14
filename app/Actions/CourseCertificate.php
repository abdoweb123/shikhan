<?php

namespace App\Actions;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Anam\PhantomMagick\Converter;
use Anam\PhantomLinux\Path;

class CourseCertificate
{
    public function index()
    {

        $lang = App::getLocale() ;
        $testResultId = explode( '-', \Route::input('id') )[0];
        $siteId = explode( '-', \Route::input('id') )[1];
        $user_id = \Auth::guard('web')->user()->id ;

        $row = App\member::find($user_id)->test_results()->where('id','=',$testResultId)->firstOrFail();
        // $course = App\course::where('id',$row->course_id)->select('exam_at')->firstOrFail();
        $exam_at_hijri = new \App\helpers\HijriDateHelper( strtotime($row->created_at) );
        $exam_at_hijri = $exam_at_hijri->get_year() . '-' . $exam_at_hijri->get_month() . '-' . $exam_at_hijri->get_day();

        if ($row->degree < $this->range_degree){
            return back()->withErrors(['', __('trans.less_than_70')]);
        }

        $course = $row->course;
        $message = $course->translate($row->locale);

        // new code - get certficate template -----------------------------------------------------------
        $certificateTemplate = $course->templet_lang( $siteId, $row->locale );
        if (! $certificateTemplate){
          return back()->withErrors(['','برجاء مراجعة ادارة الموقع']);
        }

        if (! isset($certificateTemplate[$row->locale]) ) {
          return back()->withErrors(['','برجاء مراجعة الإدارة']);
        }

        $message->content = $certificateTemplate[$row->locale];
        // ---------------------------------------------------------------------------------------------

        $content = view('emails.results', ['data' => $row, 'exam_at_hijri' => $exam_at_hijri, 'subject' => $message->subject, 'content' => $message->content]);
        $conv = new Converter();
        $options = [
          'format' => $course->format,
          'orientation' => $course->orientation,
          'margin' => '.1cm'
        ];
        $time=time();
        $conv->setPdfOptions($options)->addPage($content)
        ->setBinary(base_path('vendor/anam/phantomjs-2.1.1-linux-x86_64/bin/phantomjs'))
        ->save(storage_path('app/public/public/certificates/'.$row->locale.'-'.$row->id.'-'.$time.'.pdf'));

        return response()->download( storage_path('app/public/public/certificates/'.$row->locale.'-'.$row->id.'-'.$time.'.pdf') ); // ->deleteFileAfterSend(true);

    }
}
