<div class="card card-nav-tabs w-100 h-100" style="border: none;">

    <div class="card-body w-100 h-100 p-0">
        {{-- <h4 class="card-title">Special title treatment</h4> 
        <p class="card-text"> @lang('core.notify_quizs_lesson') </p>
        @if (Auth::guard('web')->check())
            @if (Auth::guard('web')->user()->courses()->find($course->id))
                <a href="{{ route('courses.unsubscription',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-danger"> @lang('core.unsubscribe') </a>
                <a href="{{ route('courses.quiz',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-success"> @lang('core.test_now') </a>
            @else
                <a href="{{ route('courses.subscription',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-info"> @lang('core.newsletter_submit') </a>
            @endif
        @else
            <a class="btn btn-denger" href="{{ route('login') }}">
                @lang('meta.title.login')
            </a>
        @endif
        @if (Auth::guard('web')->check())
            <p class="text-muted h6"> @lang('core.notify_quiz_count',['count' => intval(3 - Auth::guard('web')->user()->test_results()->count('no_test'))]) </p>
        @endif--}}
        
        
        
        
    <div class="curriculum-scrollable scrollbar-light scroll-content scroll-scrolly_visible" style="height: auto; margin-bottom: 0px; margin-right: 0px; opacity: 1; max-height: 187px;">
        <nav class="thim-font-heading learn-press-breadcrumb" itemprop="breadcrumb">
            <a href="{{ route('courses.index',$site->alias) }}">{{$site->title}}</a>
            <i class="fa-angle-right fa"></i>
            <a href="{{ route('courses.show',['site' => $site->alias,'course' => $course->alias]) }}" title="{{ $course->title }}">{{$course->title}}</a>
            <br/>
            <span class="item-name">{{ $post->title }} </span>
        </nav>
        <ul class="curriculum-sections">
            
            @foreach ($posts  as $lesson) 
                @php 
                    $to_lesson = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $lesson->started_at.':00');
                    $from_lesson = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());
                    if($to_lesson > $from_lesson){
                        $diff_lesson_in_days = $to_lesson->diffInDays($from_lesson);
                    }else{
                    
                        $diff_lesson_in_days = $to_lesson->diffInDays($from_lesson) * -1;
                    }
                    
                @endphp
                @if($diff_lesson_in_days < 0)
                    <li class="course-item-lp_lesson course-lesson" data-type="lp_lesson">
                        <span class="course-format-icon"><i class="fa fa-file-o"></i></span>
                        <div class="index">
                            <a class="lesson-title " href="{{ route('courses.post',['site' => $site->alias,'course' => $course->alias ,'post' => $lesson->alias]) }}"> {{$lesson->title }}	</a>
                            @if($lesson->iscompleted())
                                <div class="course-item-meta"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                            @endif
                        </div>
                    </li>
                    
                @elseif($diff_lesson_in_days == 0)
                    <li class="course-item-lp_lesson course-lesson" data-type="lp_lesson">
                        <span class="course-format-icon"><i class="fas fa-satellite-dish"></i></span>
                        <div class="index">
                            <a class="lesson-title " href="{{$lesson->link_zoom }}"> {{$lesson->title }}	</a>
                            <div class="course-item-meta-live">{{$to_lesson->format('h:i A')}}</div>
                        </div>
                    </li>
                
                @endif
            @endforeach
            @if (Auth::guard('web')->user()->courses()->find($course->id))
            <li class="course-item-lp_lesson course-lesson" data-type="lp_lesson">
                    <span class="course-format-icon"><i class="fa fa-question-circle"></i></span>
                    <div class="index">
                        <a class="lesson-title " href="{{ route('courses.quiz',['site' => $site->alias,'course' => $course->alias]) }}"> @lang('core.test_now')	</a>
                       
                    </div>
                </li>
            @endif
        </ul>
    </div>
        
        
    </div>
</div>
{{-- <!-- Button trigger modal -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>

<!-- Modal Core -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info btn-simple">Save</button>
      </div>
    </div>
  </div>
</div> --}}

{{--
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ss">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="ss" tabindex="1" role="dialog" aria-labelledby="ssLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ssLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div> --}}
