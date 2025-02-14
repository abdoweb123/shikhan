@extends('front.layouts.master-study')

@section('css_pagelevel')
<link rel="stylesheet" href="{{ asset('assets/front/css/lessons.css') }}">
<style type="text/css">
.col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
	float: right;
}
</style>
@endsection

@section('content')

	<div id="main" class="container">
		<div class="row">
				<div class="col-md-4 col-xs-12" style="text-align: right;">
						<div class="lesson-top visible-small">
								<!--LOOP (path)-->
								<a href="{{ route('front.index') }}" >{{ __('words.home') }} /</a>
								{{--<x-front.breadcrumb :breadCrumb="$breadCrumb" />--}}
									{{ $data->activeTranslation->first()->title }}
						</div>

						<x-flashAlert/>

						<aside>
								<div class="left-side-lesson-wrapper">
										<img src="{{ $data->getImagePath() }}" alt="" class="img-responsive">
										<ul>
												{{ $data->activeTranslation->first()->brief }}
										</ul>
								</div>

						</aside>
				</div>

				<div class="col-md-8 col-xs-12 lesson-main">
						<div class="lesson-top visible-large" style="text-align: right;">
										<!--LOOP (path)-->
										<a href="{{ route('front.index') }}" >{{ __('words.home') }} /</a>
										{{--<x-front.breadcrumb :breadCrumb="$breadCrumb" />--}}
										<br>
											{{ $data->activeTranslation->first()->title }}

											@if ($data->content)
												@if ($data->content->activeTranslation->isNotEmpty())
													<div style="padding: 1px 0px 0px 0px;">{{ $data->content->activeTranslation->first()->title }}</div>
												@endif
											@endif
											<br>
											@if ($data->lecture_type)
													<div style="padding: 1px 0px 0px 0px;">{{ $data->lecture_type->titleTranslation() }}</div>
											@endif
											<br>
											@if ($data->teacher)
													<div style="padding: 1px 0px 0px 0px;">{{ __('project.teachers') }} : {{ $data->teacher->title }}</div>
											@endif
											<br>
											{{--
											@if ($lecture->lesson)
												@if ($lecture->lesson->activeTranslation->isNotEmpty())
													<span>{{ $lecture->lesson->activeTranslation->first()->title }}</div>
												@endif
											@endif
											--}}
										</h5>

										@if ($data->isEnded())
												<div style="text-align: center;padding: 16px 0px 25px 0px;"><a style="color: red">منتهية</a></div>
										@else
												@if ($subscribtion)
															<span style="color: red">{{ __('project.subscribed_in' , [ 'var' =>  $subscribtion->created_at ] ) }}</span>
													@else
															<form method="post" action="{{ route('front.lectures.subscribe' , [ 'id' => $data->id ] ) }}">
																@csrf
																<div style="text-align: center;padding: 16px 0px 25px 0px;"><button type="submit" class="subscrib_but">اشترك الآن</button></div>
															</form>
												@endif
										@endif



						</div>
				</div>





				</div>





        </div>
    </div>




@section('js_pagelevel')

@endsection

@endsection
