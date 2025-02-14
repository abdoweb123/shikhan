
<!--  test -->
<div class="container" style="padding-top: 30px;">
  <div class="row">
    <div class="text-left col-md-12">

      @if (isset($lessonResponses))
        @foreach($lessonResponses as $lessonResponse)
          <div style="border: 1px solid #c6c5c5;border-radius: 8px;padding: 15px;margin-bottom: 20px;">
            <div><span style="color: gray;">{{ __('words.date') }}</span> : <span>{{ $lessonResponse->created_at }}</span></div>
            <div><span>{{ $lessonResponse->description }}</span></div>
          </div>
        @endforeach
      @endif

      <div class="col-lg-12"><h3 style="padding-bottom: 15px;">{{ $lesson->title }}</h3></div>

      <form enctype="multipart/form-data"
          method="post"
          onsubmit="ajaxFormWithFiles(event,this,'','','')"
          action="{{ route('front.enrolls.courses.lessons.store_lesson_research', [
            'enrolled' => $enrolled->id,
            'course' => $enrolled->enrolled_terms->first()->enrolled_term_courses->first()->course_id,
            'lesson' => $lesson->id
          ])
        }}">

        @csrf

        <div class="row">
          <div class="col-lg-12">
            <input type="file" class="form-control" name="files[]" multiple />
          </div>
          <div class="col-lg-12" style="padding-top: 15px;">
            <textarea type="text" class="form-control" name="description" max='5000' placeholder="{{ __('words.description') }}"></textarea>
          </div>
        </div>

        <div class="row" style="padding: 20px 0px 0px 0px;">
          <div class="col-lg-12" style="text-align: left;">
            <div id="loading_div"></div>
            <button type="submit" class="btn btn-success" style="padding: 7px 40px;"> @lang('general.send') </button>
          </div>
        </div>

      </form>


    </div>
  </div>
</div>
