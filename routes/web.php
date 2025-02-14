<?php

use App\Http\Controllers\Auth\LoginTeacherController;
use App\Http\Controllers\back\CourseController;
use App\Http\Controllers\back\index;
use App\Http\Controllers\back\LessonController;
use App\Http\Controllers\back\QuestionController;
use App\Http\Controllers\back\TermController;
use App\Http\Controllers\back\TestController;
use App\Http\Controllers\front\CertificateController;
use App\Http\Controllers\front\courses;
use App\Http\Controllers\front\profile;
use App\Http\Controllers\front\StudyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;




Route::get('download_certificate/{file}','download@downloadCertificate')->name('download_certificate');

// Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['admin', 'auth:admin']], function () {
//     \UniSharp\LaravelFilemanager\Lfm::routes();
// });

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function()
{

    // IsTeacher Authentication
    Route::group(['prefix'=>'teachers'], function (){
        Route::get('login',[LoginTeacherController::class,'showLoginForm'])->name('login.teacher');
        Route::post('login',[LoginTeacherController::class,'login'])->name('login.post.teacher');
        Route::post('logout',[LoginTeacherController::class,'logout'])->name('logout.teacher');

        Route::get('dashboard',[index::class,'index'])->name('teacher.dashboard')->middleware('is_teacher');
    });


    Route::group( ['prefix' => 'dashboard','namespace' => 'back','as' => 'dashboard.', 'middleware' => ['admin', 'admin.share']], function()
    {
        // Admin
        Route::get('login','Auth\LoginController@showLoginForm')->name('login');
        Route::post('login','Auth\LoginController@login');
        Route::post('logout','Auth\LoginController@logout')->name('logout');

        Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');


        Route::group( ['middleware' => ['admin_or_teacher']], function()
        {
            Route::resource('members','members');
            Route::get('members_info', 'members@getUsersInfo')->name('users.info');
            Route::post('members_details', 'members@getUserDetails')->name('user.details');
            Route::post('members_reset_password', 'members@resetPassword')->name('user.reset.password');
            Route::get('members_extra_trays/{member}', 'members@showExtraTrays')->name('user.show_extra_trays');
            Route::post('members_extra_trays/store', 'members@storeExtraTrays')->name('user.store_extra_trays');
            Route::post('members_extra_trays/update', 'members@updateExtraTrays')->name('user.update_extra_trays');
        });



        Route::group( ['middleware' => ['auth:admin']], function()
        {
            Route::get('/','index@index')->name('index');
            Route::get('/create_sitemap', 'sitemapController@create_sitemap');
            Route::get('/PrevUrl', 'PrevUrlController@index')->name('PrevUrl');
            Route::get('members/export', 'members@export_')->name('members.export');

            Route::post('create_user_password', 'members@createUserPassword')->name('create.user.password');
            Route::post('create_users_passwords', 'members@createUsersPasswords')->name('create.users.passwords');
            Route::post('send_users_passwords', 'members@sendUsersPasswords')->name('send.users.passwords');

            Route::get('download_source_certificate/{id}/{type}/{user_id}','members@certificates_show_admin')->name('certificates-show');

            Route::put('members/{member}/status','members@status')->name('members.status');
            // Route::put('members/{member}','members@update')->name('members.update');

            // Route::resource('{site}/courses','CourseController');
            Route::group([
                'prefix' => 'courses',
            ], function () {
                Route::get('to_assign_index','CourseController@to_assign_index')->name('courses.to_assign_index');
                Route::post('to_assign_index','CourseController@to_assign_index_post')->name('courses.to_assign_index.post');

                Route::get('{site}/', [CourseController::class,'getAll'])->name('courses.getAll');
                Route::get('{site}/{term?}', 'CourseController@index')->name('courses.index');
                Route::get('{site}/create/course', [CourseController::class,'create'])->name('courses.create');
                Route::post('{site}/store', 'CourseController@store')->name('courses.store');
                Route::get('{site}/courses/{course}/edit', 'CourseController@edit')->name('courses.edit');
                Route::PUT('{site}/courses/{course}', 'CourseController@update')->name('courses.update');
                Route::PUT('{site}/courses/{course}/status', 'CourseController@status')->name('courses.status');
                Route::DELETE('{site}/courses/{course}/delete', 'CourseController@destroy')->name('courses.destroy');

                Route::get('/create/{course_id}/track',[CourseController::class,'createTrack'])->name('track.create');
                Route::post('/track',[CourseController::class,'storeTrack'])->name('track.store');
                Route::get('/edit/{course_id}/track',[CourseController::class,'editTrack'])->name('track.edit');
                Route::post('update/track',[CourseController::class,'updateTrack'])->name('track.update');
            });

            // Route::put('{site}/courses/{course}/status','courses@status')->name('courses.status');


            Route::group([
                'prefix' => 'statistics',
            ], function () {
                Route::get('daily_registerd','StatisticController@getDailyRegisterd')->name('statistics.daily_registerd');
                // Route::get('daily','StatisticController@getDaily')->name('statistics.daily');
                // Route::get('export/{statistic}/{type?}','StatisticController@export')->name('statistics.export');
            });

            Route::post('updatelink','CourseController@updatelink')->name('courses.updatelink');
            Route::get('{site}/courses/{course}/template','CourseController@edit_template')->name('courses.template.edit');
            Route::put('{site}/courses/{course}/template','CourseController@update_template')->name('courses.template.update');


            // questions
            Route::get('{site}/courses/{course}/questions/create','QuestionController@createQuestion')->name('courses.questions.create');
            Route::post('{site}/courses/{course}/questions/store','QuestionController@storeQuestion')->name('courses.questions.store');

            Route::get('{site}/courses/{course}/questions','QuestionController@edit')->name('courses.questions.edit');
            Route::PUT('questions/{id}/update','QuestionController@update')->name('courses.questions.update');

            Route::get('questions/{id}/translations','QuestionController@getQuestionTranslations')->name('courses.questions.translations');
            Route::PUT('questions/{id}/translations','QuestionController@updateQuestionTranslations')->name('courses.questions.translations.update');

            Route::get('answers/{id}/translations','QuestionController@getAnswerTranslations')->name('courses.answers.translations');
            Route::PUT('answers/{id}/translations','QuestionController@updateAnswerTranslations')->name('courses.answers.translations.update');

            Route::delete('questions/{id}/delete','QuestionController@delete')->name('courses.questions.delete');
            Route::delete('course/{course}/questions/delete','QuestionController@delete')->name('courses.questions.delete_all');

            Route::post('{site}/courses/{course}/questions/import','QuestionController@import')->name('courses.questions.import');
            Route::get('{site}/courses/{course}/questions/export','QuestionController@export')->name('courses.questions.export');
            //////////////////////


            Route::get('translations/{lang}/to_csv','TranslationController@convertToCsv')->name('translation.convert.csv');
            Route::resource('translations','TranslationController');



            Route::resource('{site}/courses/{course}/sender','sender');
            Route::put('{site}/courses/{course}/sender/{sender}/status','sender@status')->name('sender.status');

            Route::get('{site}/courses/{course}/test_results/send_all','test_results@send_all')->name('test_results.send_all');
            Route::resource('{site}/courses/{course}/test_results','test_results');
            Route::post('{site}/courses/{course}/test_results/import','test_results@import')->name('test_results.import');
            Route::get('{site}/courses/{course}/test_results/export/{type}','test_results@export')->name('test_results.export');
            Route::get('{site}/courses/{course}/test_results/{test_result}/send','test_results@send')->name('test_results.send');

            Route::resource('{site}/courses/{course}/subscribers','subscribers', [
                'only' => ['index', 'show', 'create','store', 'destroy']
            ]);

            Route::get('send_email','SendEmailsController@index')->name('send_emails.index');
            Route::post('send_email','SendEmailsController@index')->name('send_emails.index');
            Route::get('send_email_edit','SendEmailsController@edit')->name('send_emails.edit');
            Route::get('send_email_edit_details/{id}','SendEmailsController@editDetails')->name('send_emails.edit.details');
            Route::post('send_email_update_details/{id}','SendEmailsController@updateDetails')->name('send_emails.update.details');
            Route::post('send_email_update_status','SendEmailsController@updateStatus')->name('send_emails.update.status');


            Route::get('send_notification_inner','SendNotificationsInnerController@index')->name('send_notifications.inner.index');
            Route::post('send_notification_inner','SendNotificationsInnerController@index')->name('send_notifications.inner.index');
            Route::get('notification_inner_template','SendNotificationsInnerController@editTemplate')->name('send_notifications.inner.edit_template');
            Route::post('notification_inner_template','SendNotificationsInnerController@storeTemplate')->name('send_notifications.inner.store_template');


            Route::get('courses_certificates_templates','CoursesCertificatesTemplatesController@index')->name('courses.certificates.templates');
            Route::post('courses_certificates_templates/{id}','CoursesCertificatesTemplatesController@storeTemplates')->name('courses.certificates.templates.update');

            Route::group(['prefix' => 'diplomas'], function () {
                Route::get('/', 'DiplomaController@index')->name('diplomas.index');
                Route::get('create', 'DiplomaController@create')->name('diplomas.create');
                Route::post('store', 'DiplomaController@store')->name('diplomas.store');
                Route::get('{id}/edit', 'DiplomaController@edit')->name('diplomas.edit')->where('id', '[0-9]+');
                Route::post('{id}/edit', 'DiplomaController@edit')->name('diplomas.edit')->where('id', '[0-9]+');
                Route::PUT('{id}', 'DiplomaController@update')->name('diplomas.update')->where('id', '[0-9]+');
                Route::post('{id}/store_translate', 'DiplomaController@storeTrans')->name('diplomas.store_translate')->where('id', '[0-9]+');
                Route::PUT('{id}/status', 'DiplomaController@setActive')->name('diplomas.status')->where('id', '[0-9]+');
                Route::DELETE('delete', 'DiplomaController@destroy')->name('diplomas.destroy');
            });

            // Terms of classroom
            Route::group(['prefix' => 'terms'], function () {
                Route::get('/', [TermController::class,'index'])->name('terms.index');
                Route::get('create', [TermController::class,'create'])->name('terms.create');
                Route::post('store', [TermController::class,'store'])->name('terms.store');
                Route::get('{id}/edit', [TermController::class,'edit'])->name('terms.edit');
                Route::PUT('{id}/update', [TermController::class,'update'])->name('terms.update');
                Route::PUT('{id}/status', [TermController::class,'setActive'])->name('terms.status');
                Route::DELETE('delete', [TermController::class,'destroy'])->name('terms.destroy');

            });

            // Tests of Course
            Route::group(['prefix' => 'tests'], function () {
                Route::get('/{course?}', [TestController::class,'index'])->name('tests.index');
                Route::get('{site}/create/{course}', [TestController::class,'create'])->name('tests.create');
                Route::post('/store', [TestController::class,'store'])->name('tests.store');
                Route::get('/{test}/edit', [TestController::class,'edit'])->name('tests.edit');
                Route::put('/{test}', [TestController::class,'update'])->name('tests.update');
                Route::delete('/{test}', [TestController::class,'destroy'])->name('tests.destroy');
            });

            // Questions of Test
            Route::group(['prefix' => 'test-questions',], function () {
                Route::controller(QuestionController::class)->group(function () {
                    Route::get('{test}/questions','index')->name('questions_test.index');
                    Route::post('{test}/questions/import','import')->name('questions_test.import');
                    Route::get('{test}/questions/export','export')->name('questions_test.export');
                    Route::delete('{test}/questions/delete_all','destroyAll')->name('questions_test.destroy_all');
                    Route::post('{test}/questions','store')->name('questions_test.store');
                    Route::put('/{question}','update')->name('questions_test.update');
                    Route::get('/{question}','destroy')->name('questions_test.destroy');
                    Route::get('/{answer}/question-answer','deleteAnswer')->name('questions_test.deleteAnswer');

                //   Route::put('questions/{question}','update')->name('questions_test.update');
                //   Route::delete('questions/{question}','destroy')->name('questions_test.destroy');
                });
            });

            Route::group([
                'prefix' => 'prize',
            ], function () {
                Route::get('/', 'MembersPrizeController@index')->name('prize.index');
                Route::get('create', 'MembersPrizeController@create')->name('prize.create');
            });

            Route::group([
                'prefix' => 'lessons',
            ], function () {
                Route::get('{course?}', [LessonController::class,'index'])->name('lessons.index');
                Route::get('create/lesson', [LessonController::class,'create'])->name('lessons.create');
                Route::post('store', [LessonController::class,'store'])->name('lessons.store');
                Route::get('{id}/edit', [LessonController::class,'edit'])->name('lessons.edit')->where('id', '[0-9]+');
                // Route::post('{id}/edit', 'LessonController@edit')->name('lessons.edit')->where('id', '[0-9]+');
                Route::put('update/{lesson}', [LessonController::class,'update'])->name('lessons.update')->where('id', '[0-9]+');
                Route::post('{id}/store_translate', [LessonController::class,'storeTrans'])->name('lessons.store_translate')->where('id', '[0-9]+');
                Route::PUT('{id}/status', [LessonController::class,'setActive'])->name('lessons.status')->where('id', '[0-9]+');
                Route::DELETE('delete/{id?}', [LessonController::class,'destroy'])->name('lessons.destroy');
                Route::get('delete/{id?}', [LessonController::class,'deleteItem'])->name('lessons.delete_item');
            });
            Route::get('serach_option_values/{crit?}','OptionController@searchOptionValues')->name('item.search_option_values');

            Route::group([
                'prefix' => 'teachers',
            ], function () {
                Route::get('/', 'TeacherController@index')->name('teachers.index');
                Route::get('create', 'TeacherController@create')->name('teachers.create');
                Route::post('store', 'TeacherController@store')->name('teachers.store');
                Route::get('{id}/edit', 'TeacherController@edit')->name('teachers.edit')->where('id', '[0-9]+');
                Route::post('{id}/edit', 'TeacherController@edit')->name('teachers.edit')->where('id', '[0-9]+');
                Route::PUT('{id}', 'TeacherController@update')->name('teachers.update')->where('id', '[0-9]+');
                Route::post('{id}/store_translate', 'TeacherController@storeTrans')->name('teachers.store_translate')->where('id', '[0-9]+');
                Route::PUT('{id}/status', 'TeacherController@setActive')->name('teachers.status')->where('id', '[0-9]+');
                Route::DELETE('delete', 'TeacherController@destroy')->name('teachers.destroy');
            });



            Route::group([
                'prefix' => 'partners',
            ], function () {
                Route::get('/', 'PartnerController@index')->name('partners.index');
                Route::get('create', 'PartnerController@create')->name('partners.create');
                Route::post('store', 'PartnerController@store')->name('partners.store');
                Route::get('{id}/edit', 'PartnerController@edit')->name('partners.edit')->where('id', '[0-9]+');
                Route::post('{id}/edit', 'PartnerController@edit')->name('partners.edit')->where('id', '[0-9]+');
                Route::PUT('{id}', 'PartnerController@update')->name('partners.update')->where('id', '[0-9]+');
                Route::post('{id}/store_translate', 'PartnerController@storeTrans')->name('partners.store_translate')->where('id', '[0-9]+');
                Route::PUT('{id}/status', 'PartnerController@setActive')->name('partners.status')->where('id', '[0-9]+');
                Route::DELETE('delete', 'PartnerController@destroy')->name('partners.destroy');
            });



            Route::group([
                'prefix' => 'info',
            ], function () {
                Route::get('/', 'PageController@index')->name('pages.index');
                Route::post('/', 'PageController@index')->name('pages.index');
                Route::get('create', 'PageController@create')->name('pages.create');
                Route::post('store', 'PageController@store')->name('pages.store');
                Route::get('{id}/edit', 'PageController@edit')->name('pages.edit')->where('id', '[0-9]+');
                Route::post('{id}/edit', 'PageController@edit')->name('pages.edit')->where('id', '[0-9]+');
                Route::PUT('{id}', 'PageController@update')->name('pages.update')->where('id', '[0-9]+');
                Route::post('{id}/store_translate', 'PageController@storeTrans')->name('pages.store_translate')->where('id', '[0-9]+');
                Route::PUT('{id}/status', 'PageController@setActive')->name('pages.status')->where('id', '[0-9]+');
                Route::DELETE('{id}/delete', 'PageController@destroy')->name('pages.destroy');
            });

            Route::group([
                'prefix' => 'social',
            ], function () {
                Route::get('/', 'socialController@index')->name('social.index');
                Route::get('create', 'socialController@create')->name('social.create');
                Route::post('store', 'socialController@store')->name('social.store');
                Route::get('{id}/edit', 'socialController@edit')->name('social.edit')->where('id', '[0-9]+');
                Route::post('{id}/edit', 'socialController@edit')->name('social.edit')->where('id', '[0-9]+');
                Route::PUT('{id}', 'socialController@update')->name('social.update')->where('id', '[0-9]+');
                Route::post('{id}/store_translate', 'socialController@storeTrans')->name('social.store_translate')->where('id', '[0-9]+');
                Route::PUT('{id}/status', 'socialController@setActive')->name('social.status')->where('id', '[0-9]+');
                Route::DELETE('delete', 'socialController@destroy')->name('social.destroy');
            });

            Route::post('login_user','members@loginWithOutPassword')->name('login_user');
            Route::post('change_user_status','members@changeUserStatus')->name('change_user_status');


            // Partners
            Route::get('/pay_review', 'PayController@review')->name('pay.review');
            Route::post('/pay_review/update', 'PayController@reviewUpdate')->name('pay.review.update');

        });
    });
});






    Route::get('temp100/{alias?}','front\TempControllor@t1')->middleware(['redirect.if.not.verified','redirect.if.hasnt.id']);
    Route::get('correct_static_results','front\TempControllor@correctStaticResults');
    Route::get('temp0','front\TempControllor@t1')->name('t1');


    Route::get('login_user','front\profile@loginWithOutPassword')->name('front.login_user');
    Route::get('/sitemap.xml', 'front\sitemapController@index_all');
    Route::post('validate_name','front\Auth\RegisterController@validateNameAjax')->name('validate_name')->middleware('throttle:30,1');
// Route::post('register_every_page','front\Auth\RegisterController@registerEveryPage')->name('register_every_page');




    Route::get('/clear-cache', function() {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('clear-compiled');
        return "Cache is cleared";
    })->name('clear-cache');

    Route::get('/create_link', function() {
        Artisan::call('storage:link');
        return "Link created";
    })->name('create_link');

    Route::get('optimize', function() { $exitCode = Artisan::call('optimize');return 'Cash optimize'; });
    Route::get('config-cache', function() { $exitCode = Artisan::call('config:cache');return 'config:cache'; });





    Route::group( ['namespace' => 'front', 'middleware' => ['web','www','must.change.name']], function()
    {
        // sitemap
        Route::get('{lang}/sitemap.xml', 'sitemapController@index');
        Route::get('{lang}/sitemap/{name}', 'sitemapController@view_sitemap');

        // pay
        Route::get('{lang}/pay','PaymobController@pay')->name('payment.pay');
        route::get('{lang}/callback',function(){
            return view('payment.success');
        });



        Route::get('{lang}/d/{alias}','shortLinkController@sites')->name('sites.short_link');
        Route::get('{lang}/c/{alias}','shortLinkController@courses')->name('courses.short_link');

        Route::get('{lang}/course_increment_likes/{id}', 'courses@incrementLikes')->name('front.course.increment_likes')->where('id', '[0-9]+')->middleware('throttle:1,1');


        // statistics from static tables
        Route::get('{lang}/main_statistics_static', 'ReportStaticController@mainStatistics')->name('front.main_statistics');
        Route::post('{lang}/main_statistics_static', 'ReportStaticController@mainStatistics')->name('front.main_statistics_search'); // اجاكس
        // statistics dynamic
        // Route::get('{lang}/main_statistics', 'ReportController@mainStatistics')->name('front.main_statistics');
        // Route::post('{lang}/main_statistics', 'ReportController@mainStatistics')->name('front.main_statistics_search'); // اجاكس


        Route::get('{lang}/home_invisible_part','IndexController@getHomeInvisiblePart')->name('home_invisible_part');

        Route::get('{lang}/sites_statistics', 'ReportController@sitesStatistics')->name('front.sites_statistics');
        Route::post('{lang}/sites_statistics', 'ReportController@sitesStatistics')->name('front.sites_statistics');
        Route::get('{lang}/sites_statistics_static', 'ReportSitesController@sitesStatistics')->name('front.sites_statistics_static');


        Route::get('{lang}/ajax_courses', 'ReportController@ajax_courses')->name('front.ajax_courses'); // غير مستخدم تم حذف الصف الئى يستخدم هذا الروت


        Route::get('{lang}/rpt_test', 'ReportGLobalController@rptTest')->name('front.sites.rpt_test');

        // subscripe users where already subecribed in more than 70% of courses make them sybscripe in all courses
        Route::get('{lang}/subscribe_users_in_courses', 'ActionsGLobalController@SubscribeUsersInCourses')->name('front.subscribe_users_in_courses');

        Route::get('member_lesson_seen','courses@memberLessonSeen')->name('member_lesson_seen');




        Route::prefix(\LaravelLocalization::setLocale())->middleware('localeSessionRedirect')->group(function()
        {

            Route::get('quiz_result','quiz@result')->name('quiz_result'); // ->middleware('prevent.back.history');
            Route::get('quiz_answers/{id}','quiz@showAnswers')->name('quiz_answers')->where('id', '[0-9]+');

            Route::get('user_results_out','ResultsOutController@showUserResultsOut')->name('front.show_user_results_out');
            Route::post('user_results_out','ResultsOutController@getUserResultsOut')->name('front.get_user_results_out'); // throttel

            Route::post('register_every_page','Auth\RegisterController@registerEveryPage')->name('register_every_page');


            Route::get('complete_lesson/{id}', 'courses@complete_lesson')->name('complete_lesson')->where('id', '[0-9]+');
            Route::get('/teachers','teacherController@index')->name('teacher.index');
            Route::get('/teachers/{name}','teacherController@show')->name('teachers.show');
            Route::get('/rated/teachers','teacherController@rated')->name('teachers.rated');
            Route::get('/partners', 'PartnersController@index')->name('partners.index');
            Route::get('/partners/{alias}', 'PartnersController@show')->name('partners.show');
            Route::get(LaravelLocalization::transRoute('meta.alias.diplomas'),'DiplomaController@index')->name('diplomas.index');
            Route::get('/landing1','IndexController@landing1')->name('landing.index');

            Route::get('/prizes', 'PrizeController@index')->name('front.prizes.index');
            Route::post('/prizes_subscripe', 'PrizeController@subscripe')->name('front.prizes.subscripe');
            Route::post('/add_link_share', 'PrizeController@add_link_share')->name('front.prizes.add_link_share');
            Route::get('/prizes_type_publish', 'PrizeController@indexPublish')->name('front.prizes.index.publish');
            // Route::get('/getreportcousres/{id}', 'index@getreportcousres')->name('front.getreportcousres')->where('id', '[0-9]+');

            Route::post('subscribers/{site}','SubscriptionController@subscribe')->name('diplomas.subscribers');
            Route::get('unsubscribers/{site}','SubscriptionController@unSubscribe')->name('diplomas.unsubscribers');
            Route::get('/diploma_subscribe_from_outside/{site_alias?}','SubscriptionController@subscripeInSitesFromOutside')->name('front.subscribtion.from_outside');
            Route::get('/prizes_subscribe_from_outside/{course_id}/{outside}', 'PrizeController@showSubscribeFromOutside')->name('front.prizes.show_subscribe_from_outside')->middleware('throttle:5,1');
            Route::post('/prizes_subscribe_from_outside/{course_id}/{outside}', 'PrizeController@subscribeFromOutside')->name('front.prizes.subscribe_from_outside')->middleware('throttle:5,1');



            Route::group([
                'prefix' => 'page',
            ], function () {
                Route::get('/contactus', 'InfoController@contactUs')->name('front.page.contact_us');
//                 Route::post('/contactus', 'InfoController@contactUsPost')->name('front.page.contact_us_post')->middleware('throttle:3,1');
                Route::get('/faqs', 'InfoController@ViewFaqs')->name('front.page.faqs');
            });

            // Route::group([
            // 	'prefix' => 'reading',
            //   'namespace' => 'Reading'
            //     ], function () {
            // 			Route::get('/', 'HomeController@index')->name('front.reading.home');
            //       Route::get('/groups/{group}', 'CourseController@index')->name('front.reading.courses');
            // });

            Route::group([
                'prefix' => 'info',
            ], function () {
                Route::get('/{alias?}', 'InfoController@show')->name('front.info.show');
            });

            Route::get('render_successed_users_country_site/{site_id}/{country_id}','courses@renderSuccessedUsersOfCountryOfSite')->name('render_uccessed_users_country_site');
            Route::get(LaravelLocalization::transRoute('meta.alias.successed_users_site'),'courses@getSuccessedUsersOfCountryOfSite')->name('successed_users_site');
            Route::get(LaravelLocalization::transRoute('meta.alias.successed_users_country_site'),'courses@getSuccessedUsersOfCountryOfSite')->name('successed_users_country_site');


            // must Auth
            Route::group(['middleware' => ['auth:web','verified','must.change.name.prevent.account']], function()
            {
                Route::match(['get', 'post'],LaravelLocalization::transRoute('meta.alias.profile'),'profile@index')->name('profile'); // ->withoutMiddleware(['must.change.name']);
                Route::post('clear_my_photo','profile@clearMyPhoto')->name('clear_my_photo');
                Route::post('correct_email','profile@correctEmail')->name('correct_email');

                Route::get('show_verification_email','Auth\VerifyController@showVerificationEmail')->name('show_verification_email')->middleware('throttle:10,1');
                Route::get('verify_verification_email/{token}','Auth\VerifyController@verifyVerificationEmail')->name('verify_verification_email');



                // Route::get(LaravelLocalization::transRoute('meta.alias.my_quizzes'),'profile@my_quizzes')->name('my_quizzes');
                Route::get(LaravelLocalization::transRoute('meta.alias.my_courses_cirts'),'profile@getSitesCertificates')->name('sites_certificates');
                Route::get('my_courses_data','profile@getCoursesCertificates')->name('my_courses_data'); // for ajax
                Route::get(LaravelLocalization::transRoute('meta.alias.my_courses_cirts_details'),'profile@getCoursesCertificates')->name('courses_certificates');
                Route::get(LaravelLocalization::transRoute('meta.alias.courses_certificates_term'),[profile::class,'getCoursesCertificatesOfTerm'])->name('courses_certificates_term');

                Route::get(LaravelLocalization::transRoute('meta.alias.my_courses'),'profile@my_courses')->name('my_courses'); // will delete
                Route::get(LaravelLocalization::transRoute('meta.alias.certificates'),'profile@certificates')->name('certificates'); // will delete
                Route::get(LaravelLocalization::transRoute('meta.alias.certificates1'),'profile@certificates_test')->name('certificates1');

                Route::get(LaravelLocalization::transRoute('meta.alias.certificates-show'),'profile@certificates_show')->name('certificates-show');
                // replaced with this
                Route::get(LaravelLocalization::transRoute('meta.alias.download_certificate'),'CertificateController@downloadCertificate')->name('download-certificate')->middleware(['redirect.if.not.verified']);
                Route::get(LaravelLocalization::transRoute('meta.alias.site-certificates-show'),'CertificateController@downloadSiteCertificate')->name('site-certificate-show')->middleware(['redirect.if.not.verified','redirect.if.hasnt.id']);
                Route::get(LaravelLocalization::transRoute('meta.alias.term-certificates-show'),[CertificateController::class,'downloadTermCertificate'])->name('term-certificate-show')->middleware(['redirect.if.not.verified','redirect.if.hasnt.id']);
                Route::get(LaravelLocalization::transRoute('meta.alias.site-courses-certificates-show'),'CertificateController@downloadSiteCoursesCertificate')->name('download-site-courses-certificate-show')->middleware(['redirect.if.not.verified','redirect.if.hasnt.id']);
                Route::get(LaravelLocalization::transRoute('meta.alias.extra-certificates-show'),'CertificateController@downloadExtraCertificate')->name('download-site-extra-certificate-show')->middleware(['redirect.if.not.verified','redirect.if.hasnt.id']);
                Route::get(LaravelLocalization::transRoute('meta.alias.ejaza-certificates-show'),'CertificateController@downloadEjazaCertificate')->name('download-ejaza-certificate-show'); //->middleware(['redirect.if.not.verified']);
                Route::get(LaravelLocalization::transRoute('meta.alias.main-advanced-site-certificates-show'),'CertificateController@downloadMainAdvancedSiteCertificate')->name('download-main-advanced-site-certificate-show');
                Route::get(LaravelLocalization::transRoute('meta.alias.details-advanced-site-certificates-show'),'CertificateController@downloadDetailsAdvancedSiteCertificate')->name('download-details-advanced-site-certificate-show');
                Route::get(LaravelLocalization::transRoute('meta.alias.download_term_certificate'),'CertificateController@downloadTermCertificate')->name('download-term-certificate')->middleware(['redirect.if.not.verified']);


                Route::get(LaravelLocalization::transRoute('meta.alias.courses.course-tests-visual-show'), 'CourseTestVisualController@show')->name('front.course_tests_visual_show');
                Route::post('course-tests-visual-upload', 'CourseTestVisualController@upload')->name('front.course_tests_visual_upload');
                Route::post('course-tests-visual_delete', 'CourseTestVisualController@delete')->name('front.course_tests_visual_delete');
                Route::get(LaravelLocalization::transRoute('meta.alias.courses.course-tests-visual-correction'), 'CourseTestVisualController@quizCorrection')->name('front.course_tests_visual_correction');
                Route::post('course-tests-visual-correct', 'CourseTestVisualController@correct')->name('front.course_tests_visual_correct');

            });

            Auth::routes(['verify' => true]);

            Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider')->name('socialite.index');
            Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback')->name('socialite.callback');
            Route::get('register/{driver}', 'Auth\RegisterController@redirectToProvider')->name('socialite.index.register');

            Route::get(LaravelLocalization::transRoute('meta.alias.notifications_inner'),'SendNotificationsInnerController@index')->name('notifications_inner_index')->middleware('auth:web');
            Route::post('delete_notification_inner','SendNotificationsInnerController@delete')->name('send_notifications.inner.delete')->middleware('auth:web');

            Route::group(['as' => 'courses.'], function ()
            {
                Route::group(['middleware' => ['verified','must.change.name.prevent.account']], function ()
                {
                    Route::get(LaravelLocalization::transRoute('meta.alias.courses.quiz'),'quiz@index')->name('quiz'); // ->middleware('prevent.back.history');
                    Route::post(LaravelLocalization::transRoute('meta.alias.courses.quiz'),'quiz@store')->name('quiz'); // ->middleware('prevent.back.history');
                    Route::get(LaravelLocalization::transRoute('meta.alias.courses.quiz_term'),'quiz@indexTerm')->name('quiz_term');
                    Route::post(LaravelLocalization::transRoute('meta.alias.courses.quiz_term'),'quiz@storeTerm')->name('quiz_term');

                    Route::get(LaravelLocalization::transRoute('meta.alias.courses.quiz1'),'quiz@index1')->name('quiz1');
                    Route::post(LaravelLocalization::transRoute('meta.alias.courses.quiz1'),'quiz@store1');

                    Route::get(LaravelLocalization::transRoute('meta.alias.courses.subscription'),'courses@subscription')->name('subscription');
                    Route::get(LaravelLocalization::transRoute('meta.alias.courses.unsubscription'),'courses@unsubscription')->name('unsubscription');
                    Route::get(LaravelLocalization::transRoute('meta.alias.courses.ajax.subscription'),'courses@subscription')->name('ajax.subscription');
                    Route::get(LaravelLocalization::transRoute('meta.alias.courses.ajax.unsubscription'),'courses@unsubscription')->name('ajax.unsubscription');
                });
//                Route::get(LaravelLocalization::transRoute('meta.alias.courses.post'),'courses@post')->name('post')->middleware('auth:web');
                Route::get(LaravelLocalization::transRoute('meta.alias.courses.show'),[StudyController::class,'showCourse'])->name('show')->middleware(['prevent.back.history','auth:web']);
//                Route::get('test/{site}/{course}/{courseable_id?}/{courseable_type?}', [StudyController::class,'showCourse'])->name('showCourseable');
                Route::get(LaravelLocalization::transRoute('meta.alias.show_special'),'courses@special')->name('special');
                Route::get(LaravelLocalization::transRoute('meta.alias.courses.index'),'courses@index')->name('index');

//                Route::get('get/courseTrack/courseable/{site}/{course}',[StudyController::class,'showCourseableOfCourseTrack'])->name('courseTrack.courseable');

            });

        });

        Route::get(LaravelLocalization::getCurrentLocale(),'IndexController@home')->name('home');
        Route::get(LaravelLocalization::transRoute('meta.alias.index'),'IndexController@index')->name('index'); // for redirect if no lanuage


        // Show get lesson/test data of courseTrack
        Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function()
        {
            Route::get('courseTrack/{course}/{lesson_id}/lesson', [StudyController::class,'getCourseTrackLesson'])->name('courseTrack.getCourseTrackLesson');
            Route::get('courseTrack/{course}/{test_id}/test', [StudyController::class,'getCourseTrackTest'])->name('courseTrack.getCourseTrackTest');
            Route::post('{course}/tests/{test}', [StudyController::class,'testResult'])->name('courses.tests.testResult');
//            Route::post('courseTrack/{course}/tests/{test}', [StudyController::class,'store'])->name('courses.tests.store')->middleware('enroll.check.status');
        });


    });





//Route::get(LaravelLocalization::transRoute('meta.alias.courses.showPathCourse'),'courses@showPathCourse')->name('courses.showPathCourse');


//    Route::get('show/path/course',[courses::class,'showPathCourse'])->name('courses.showPathCourse');
//    Route::get('courses/{course}/tests/{test}',[StudyController::class,'getCourseTrackItem'])->name('courses.getCourseTrackItem');
