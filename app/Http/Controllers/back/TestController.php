<?php

namespace App\Http\Controllers\back;
use App\course;
use App\Http\Controllers\Controller;
use App\Services\LanguageService;
use Illuminate\Http\Request;

use App\Models\Test;
//use App\Models\Course;
use App\Services\LanguageService2;
use App\Services\TestService;
use App\Services\CourseService;
use App\Services\TeacherService;
use App\Services\LookupService;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class TestController extends Controller
{
    private $inputLocale;
    private $morphId;
    private $morphType;
    private $morphModel;

    public function __construct(
        private LanguageService $languageService,
        private TestService $testService,
        private CourseService $courseService,
        private TeacherService $teacherService,
        private LookupService $lookupService,
        Request $request)
    {

        $this->middleware(function ($request, $next) {
            $this->inputLocale = app('inputLocale');
            $this->share(['inputLocale', $this->inputLocale ]);
            return $next($request);
         });


        $this->morphId = $request->query('id');
        $this->morphType = $request->query('type');

        $this->middleware(function ($request, $next) {

            abort_if(! ($this->morphId && $this->morphType), 500);

            if ($this->morphType == 'course'){
              $this->morphModel = \App\course::findOrFail($this->morphId);
            }

        abort_if(! $this->morphModel, 500);

        return $next($request);

        })->except('index','destroy');

    }

    public function index(Request $request)
    {

        $request->flash();

        $items = [];

        $params = [
          'title' => $request->title,
        ];


        // all tests
        if (! $this->morphId){
          $items = $this->testService->paginateWithDetails(params: $params);
        }


        // course tests
        if ($this->morphType == 'course'){
          $course = course::findOrFail($request->query('id'));
          $items = $this->courseService->paginateTestsWithDetails($course);
        }


        return view('back.content.tests.index', compact(['items']));
    }

    public function create($site, $course, Request $request)
    {
        $lessons = [];

        if ($this->morphType == 'course'){
          $lessons = $this->courseService->getLessons($this->morphModel);
        }

        return view('back.content.tests.create', array_merge($this->getLookups(), ['morphModel' => $this->morphModel, 'lessons' => $lessons]));
    }

    public function store(TestRequest $request)
    {

      $this->validateAliasAndLanguageExistsOfMorph($this->morphModel, $request->alies, $request->locale);

      $currentGuard = getCurrentGuard();

        $storeData = array_merge( $request->validated(), [
            'name' => $request->validated()['title'],
            ('created_by_'.$currentGuard.'_id') => Auth::guard($currentGuard)->id(),
          ]
        );


        try {
            DB::beginTransaction();

            $test = $this->morphModel->tests()->create($storeData);

            // the translation package will insert new translation in above line ($this->morphModel->tests())
            // this will create another translation row
             $test->translation()->create([
               'locale' => $storeData['locale'],
               'title' => $storeData['title'],
               'alias' => $storeData['alies'],
               'trans_status' => $storeData['status_id']
             ]);

            $test->lessons()->attach($storeData['lesson_ids']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['Exception details' => $e->getMessage()]);
        } catch (\Error $e) {
            DB::rollback();
            return back()->withErrors(['Error details' => $e->getMessage()]);
        }

        return redirect()->route('dashboard.tests.index')->with('success', __('messages.added_successfully'));

    }

    public function edit(Request $request)
    {

        $test = $this->morphModel->tests()->with('lessons')->where('id', $request->test)->firstOrFail();
        $translation = $test->translate($this->inputLocale);

        $lessons = [];

        if ($this->morphType == 'course'){
          $lessons = $this->courseService->getLessons($this->morphModel);
        }

        return view('back.content.tests.edit', array_merge( $this->getLookups(), [
          'data' => $test, 'translation' => $translation, 'morphModel' => $this->morphModel, 'lessons' => $lessons
        ]));

    }

    public function update(TestRequest $request)
    {

      $test = $this->morphModel->tests()->with('lessons')->where('id', $request->test)->firstOrFail();

      $this->validateAliasAndLanguageExistsOfMorph($this->morphModel, $request->alies, $request->locale, $test->id);

      $currentGuard = getCurrentGuard();

      $editData = array_merge( $request->validated(), [
          'name' => $request->validated()['title'],
          ('created_by_'.$currentGuard.'_id') => Auth::guard($currentGuard)->id(),
        ]
      );


      try {
          DB::beginTransaction();

          $test->update($editData);

          $test->translation()->updateOrCreate([
              'locale' => $request->locale,
            ],[
              'title' => $editData['title'],
              'alias' => $editData['alies'],
              'trans_status' => $editData['status_id']
          ]);

          $test->lessons()->sync($editData['lesson_ids']);

          DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        return back()->withErrors(['Exception details' => $e->getMessage()]);
      } catch (\Error $e) {
        DB::rollback();
        return back()->withErrors(['Error details' => $e->getMessage()]);
      }

      return redirect()->route('dashboard.tests.index')->with('success', __('messages.updated_successfully'));

    }

    public function destroy(Request $request, Test $test)
    {

      $test->delete();

      if ($request->expectsJson()) {
        return response()->json(['msg' => __('messages.deleted'), 'alert' => 'swal']);
      }

      return redirect()->back()->with('success', __('messages.deleted'));

    }


    private function validateAliasAndLanguageExistsOfMorph($morphModel, $alias, $locale, $currentId = null)
    {
      if ($this->testService->aliasAndLanguageExistsOfMorph($morphModel, $alias, $locale, $currentId)){
            throw ValidationException::withMessages([
              'alias' => __('messages.already_exists', [ 'var' => __('general.alias') ])
            ]);
        }
    }

    private function getLookups()
    {
        return [
            'teachers' => $this->teacherService->getSummary(),
            'testTypes' => $this->lookupService->getActiveTestTypes(),
            'getQuestions' => $this->lookupService->getActiveGetQuestionsStatuses(),
            'statuses' => $this->lookupService->getActiveStatuses(),
        ];
    }


}
