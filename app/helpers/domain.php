<?php

if(! function_exists('settingsService')){
  function settingsService()  { return app(App\Services\SettingsService::class); }
}

if(! function_exists('enrolledService')){
  function enrolledService()  { return app(App\Services\EnrolledService::class); }
}

if(! function_exists('enrolledTermService')){
  function enrolledTermService()  { return app(App\Services\EnrolledTermService::class); }
}

if(! function_exists('enrolledTermCourseService')){
  function enrolledTermCourseService()  { return app(App\Services\EnrolledTermCourseService::class); }
}

if(! function_exists('sectionCertificateService')){
  function sectionCertificateService()  { return app(App\Services\SectionCertificateService::class); }
}

if(! function_exists('enrolledStatusService')){
  function enrolledStatusService()  { return app(App\Services\EnrolledStatusService::class); }
}

if(! function_exists('lookupService')){
  function lookupService()  { return app(App\Services\LookupService::class); }
}

if(! function_exists('notificationService')){
  function notificationService()  { return app(App\Services\NotificationService::class); }
}

if(! function_exists('studentService')){
  function studentService()  { return app(App\Services\StudentService::class); }
}

if(! function_exists('studyService')){
  function studyService()  { return app(App\Services\StudyService::class); }
}

if(! function_exists('testService')){
  function testService()  { return app(App\Services\TestService::class); }
}

if(! function_exists('testResultsService')){
  function testResultsService()  { return app(App\Services\TestResultsService::class); }
}

if(! function_exists('resultsService')){
  function resultsService()  { return app(App\Services\ResultsService::class); }
}

if(! function_exists('payStatusService')){
  function payStatusService()  { return app(App\Services\PayStatusService::class); }
}

if(! function_exists('payFeeService')){
  function payFeeService()  { return app(App\Services\PayFeeService::class); }
}

// المعادلات
if(! function_exists('activeExactFaculties')){ // كليات الدراسة الفعلية
  function activeExactFaculties()  { return \App\Models\Faculty::active()->exactRealStudy()->get(); }
}
if(! function_exists('activeEquivalentDepartment')){ // كليات المعادلات
  function activeEquivalentDepartment()  { return \App\Models\Faculty::active()->equivalentRealStudy()->first(); }
}
if(! function_exists('activeExactCertificates')){ // شهادات الدراسة الفعلية
  function activeExactCertificates()  { return \App\Models\Certificate::active()->exactRealStudy()->get(); }
}
if(! function_exists('activeEquivalentCertificates')){ // شهادات المعادلة
  function activeEquivalentCertificates()  { return \App\Models\Certificate::active()->equalRealStudy()->get(); }
}




function getDegreeSuccess()
{
    return 50;
}

function getLanguages(){
  // return (new \App\Services\LanguageService())->getAll();
  return app(App\Services\LanguageService::class)->getAll();
}
function getActiveLanguages(){
  // return (new \App\Services\LanguageService())->getActiveLanguages();
  return app(App\Services\LanguageService::class)->getActiveLanguages();
}
function getDefaultLanguage(){
    // return (new \App\Services\LanguageService())->getDefaultLanguage();
    return app(App\Services\LanguageService::class)->getDefaultLanguage();
}

function getActiveCurrencies(){
  // return (new \App\Services\CurrencyService())->getActive();
  return app(App\Services\CurrencyService::class)->getActive();
}
function getDefaultCurrency(){
  // return (new \App\Services\CurrencyService())->getDefaultCurrency();
  return app(App\Services\CurrencyService::class)->getDefaultCurrency();
}


function getCurrentGuard()
{
    foreach(array_keys(config('auth.guards')) as $guard){
      if(auth()->guard($guard)->check()) return $guard;
    }
    return null;
}

function ourAuth()
{
    if (auth()->check()){
      if ( auth()->id() == 314 ){
        return true;
      }
    }

    return false;
}

function localeAuth()
{
    if (auth()->guard('admin')->check()){
      if ( auth()->guard('admin')->id() == 5 ){
        return true;
      }
    }

    return false;
}
