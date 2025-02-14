<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;

class shortLinkController extends Controller
{
    public function sites(Request $request)
    {
        $site = \App\site::where('short_link', $request->alias)->whereHas('translation', function($q) {
          return $q->where('locale', app()->getLocale());
        })->firstorfail();

        return redirect(route('courses.index',['site' => $site->slug]));
    }

    public function courses(Request $request)
    {

        $courseSite = \App\course_site::where('short_link', $request->alias)->select('site_id','course_id')->firstorfail();
        $site = \App\Translations\SiteTranslation::where('site_id', $courseSite->site_id)->where('locale', app()->getLocale())->select('alias')->firstorfail();
        $course = \App\Translations\CourseTranslation::where('course_id', $courseSite->course_id)->where('locale', app()->getLocale())->select('alias')->firstorfail();

        return redirect(route('courses.show',['site' => $site->alias,'course' => $course->alias]));

    }

}
