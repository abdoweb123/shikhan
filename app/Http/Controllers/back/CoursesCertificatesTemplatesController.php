<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Services\SettingService;
use App\Services\CourseService;
use App\Setting;
use Validator;
use Illuminate\Support\Str;

class CoursesCertificatesTemplatesController extends Controller
{
    private $settingServ;
    private $courseServ;
    private $cirtTitles = [
      'certificate_template_with_sig_pdf'=> 'قالب شهادة الدورة بتوقيع pdf',
      'certificate_template_with_sig_jpg'=> 'قالب شهادة الدورة بتوقيع jpg',
      'certificate_template_without_sig_pdf'=> 'قالب شهادة الدورة بدون توقيع pdf',
      'certificate_template_without_sig_jpg'=> 'قالب شهادة الدورة بدون توقيع jpg',
      'site_certificate_template_with_sig_pdf'=> 'قالب شهادة الدبلوم بتوقيع pdf',
      'site_certificate_template_with_sig_jpg'=> 'قالب شهادة الدبلوم بتوقيع jpg',
      'certificate_template_site_courses_degree_jpg'=> 'قالب شهادة كشف درجات الدبلوم jpg',
      'certificate_template_site_courses_degree_pdf'=> 'قالب شهادة كشف درجات الدبلوم pdf',

      'certificate_template_with_sig_pdf_2'=> 'قالب شهادة الدورة بتوقيع pdf2',
      'certificate_template_with_sig_jpg_2'=> 'قالب شهادة الدورة بتوقيع jpg2',
      'certificate_template_without_sig_pdf_2'=> 'قالب شهادة الدورة بدون توقيع pdf2',
      'certificate_template_without_sig_jpg_2'=> 'قالب شهادة الدورة بدون توقيع jpg2',
      'site_certificate_template_with_sig_pdf_2'=> 'قالب شهادة الدبلوم بتوقيع pdf2',
      'site_certificate_template_with_sig_jpg_2'=> 'قالب شهادة الدبلوم بتوقيع jpg2',
      'certificate_template_site_courses_degree_jpg_2'=> 'قالب شهادة كشف درجات الدبلوم jpg2',
      'certificate_template_site_courses_degree_pdf_2'=> 'قالب شهادة كشف درجات الدبلوم pdf2',
    ];

    public function __construct( SettingService $settingService, CourseService $courseService )
    {
        $this->settingServ = $settingService;
        $this->courseServ = $courseService;
    }

    public function index(Request $request)
    {

        $request->flush();

        $coursesCertificatesTemplates = $this->settingServ->getCoursesCertificatesTemplates();

        foreach ($coursesCertificatesTemplates as $template) {
            $langTemplates = explode('//-//', $template->value);
            $langs = [];
            foreach ($langTemplates as $key => $lang) {
                $langs[] = explode('-//-', $lang);
            }
            $template->new_value = $langs;
        }

        $cirtTitles = $this->cirtTitles;

        return view('back.content.courses.certificates',compact(['coursesCertificatesTemplates','cirtTitles']));

    }

    public function storeTemplates(Request $request)
    {
        $templateId = \Route::input('id');
        $language = $request->language;

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'language' => 'min:2',
        ]);
        if ($validator->fails()) {
            return redirect()->route('dashboard.courses.certificates.templates')->withInput()->withErrors($validator);
        }

        $template = Setting::findOrFail($templateId);

        $newValue = '';
        $langTemplates = explode('//-//', $template->value);
        foreach ($langTemplates as $key => $lang) {
            if ( substr( $lang , 0, 6 ) === $language . "-//-" ) {
                $newValue = $newValue . $language . "-//-" . $request->content . '//-//';
                $newValue = Str::replaceFirst('<div', '<div id="capture" ', $newValue); // export jpg need this id to render the main div
            } else {
              $newValue = $newValue . $langTemplates[$key] . '//-//';
            }
        }

        $template->value = rtrim($newValue, "//-//"); // remove last '//-//' from newValue
        $template->save();

        return redirect()->route('dashboard.courses.certificates.templates')->withInput()->withErrors($validator);
    }

}
