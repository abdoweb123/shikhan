@extends('front.layout.master')


@section('content')

<div class="main-banner inner-banner" id="top">

</div>

<div class="container">

  <section class="section courses" id="courses" style="padding-top: 0px; margin-top: 0px;">
    <div class="container">


      <h1 style="color: #5b49bf;padding-bottom: 25px;">{{ __('domain.my_certificates')}}</h1>

      <div class="row ">
        @foreach ($results as $enrolled)
          @if ($enrolled->isCurrent() ) <!-- عرض النتيجة للالتحاق الحالى فقط وعدم اظهارها للالاتحاقات المؤجلة -->
            <div class="col-lg-4 col-md-6 mb-30 event_outer col-md-6 design">
              <div class="events_item" style="border: 1px solid #d3d1ea;border-radius: 16px;">
                <div class="down-content" style="text-align: center;padding: 20px 18px 7px 18px;">
                  <div style="padding-bottom: 15px;">
                    <h4 style="color: #7b6ada;padding-bottom: 8px;">{{ $enrolled->faculty->title }}</h4>
                    <h4 style="color: #7b6ada;padding-bottom: 8px;">{{ $enrolled->section?->title }}</h4>
                    <h4 style="color: #7b6ada;padding-bottom: 8px;">{{ $enrolled->certificate->title }}<h4>
                    @if ($enrolled->isFinished())
                      <div style="display: flex;">
                        <div style=" width: 50%">{{ __('domain.degree') }}<br>{{ $enrolled->degree }}</div>
                        <div style=" width: 50%">{{ __('domain.rate') }}<br>{{ $enrolled->getRate() }}</div>
                      </div>
                    @endif

                    <div class="alert alert-warning" role="alert" style="font-size: 15px;font-weight: 500;">
                      نتيجة المواد التى تمت دراستها حتى الآن
                    </div>

                  </div>
                  <div style="display: flex;text-align: center;padding-top: 7px;">
                    <!-- enrolled cirtficate -->
                    @if ($enrolled->isSuccessed())

                      <div class="loading_div_{{$enrolled->id}}" style="padding: 0px 4px;margin: 0px 4px;"></div>

                      {{--
                      <div style="width: 50%">
                        <a  data-id="{{ $enrolled->id }}"
                            data-href="{{ route('front.enrolls.download.certificate', ['enrolled' => $enrolled->id, 'type' => 'jpg']) }}"
                            class="download_image btn but-default">
                            <i class="fa fa-images" style="font-size: 13px;padding: 11px 11px;border-radius: 7px;background-color: #5deca9;">&nbsp;{{ __('general.download') }} صورة&nbsp;</i>
                        </a>
                      </div>
                      --}}

                      {{--
                      <div style="width: 50%">
                        <a  data-id="{{ $enrolled->id }}"
                            data-href="{{ route('front.enrolls.download.certificate', ['enrolled' => $enrolled->id, 'type' => 'pdf']) }}"
                            class="download_image btn but-default">
                            <i class="fa fa-images" style="font-size: 13px;padding: 11px 11px;border-radius: 7px;background-color: #5deca9;">&nbsp;{{ __('general.download') }} pdf&nbsp;</i>
                        </a>
                      </div>
                      --}}
                    @endif
                  </div>






                        <!-- terms -->
                        @foreach ($enrolled->enrolled_terms as $enrolledTerm)

                            <div style="padding: 15px 0px;background-color: white;margin-bottom: 15px;border-radius: 10px;border: 1px solid #c8c6e8;">
                              <h5 style="color: #7b6ada;padding-bottom: 3px;padding-top: 11px;font-size: 23px;">{{ $enrolledTerm->term->title }}</h5>
                              @if ($enrolledTerm->isFinished()) <!-- null user didnt tested ye  -->
                                <div style="display: flex;font-weight: bold;">
                                  <div style=" width: 50%">{{ __('domain.degree') }}<br>{{ $enrolledTerm->degree }}</div>
                                  <div style=" width: 50%">{{ __('domain.rate') }}<br>{{ $enrolledTerm->getRate() }}</div>
                                </div>
                              @endif

                                  <!-- courses -->
                                  @foreach ($enrolledTerm->enrolled_term_courses as $enrolledTermCourse)
                                    @if ($enrolledTermCourse->isFinished()) <!-- null user didnt tested ye  -->
                                      <h6 style="color: #7b6ada;padding-bottom: 3px;padding-top: 11px;font-weight: bold;">{{ $enrolledTermCourse->course->title }}</h6>
                                      <div style="display: flex;">
                                        <div style=" width: 50%">{{ __('domain.degree') }}<br>{{ $enrolledTermCourse->degree }}</div>
                                        <div style=" width: 50%">{{ __('domain.rate') }}<br>{{ $enrolledTermCourse->getRate() }}</div>
                                      </div>
                                    @endif
                                  @endforeach
                            </div>

                        @endforeach
                </div>
              </div>
            </div>
          @endif
        @endforeach
      </div>


    </div>
  </section>

</div>





<div id="div-download-img" style="width: 1px;height: 1px; position: absolute;z-index: -10;" >a</div>



@push('js_pagelevel')
<script type='text/javascript'>
    $('.download_image').click(function(){

        var id = $(this).attr('data-id');
        console.log(id);

        $('.loading_div_'+id).html(
          `<span class="spinner-border spinner-border-md" role="status" aria-hidden="true"></span> `
        );

        document.getElementById('div-download-img').innerHTML = '';
        var url = $(this).attr('data-href') ;
        console.log(url);

        $.ajax({
            url: url,
            type: "GET",
            data:{},
            success: function(result){
                console.log(result);

                // from RedirectIfNotVerified middleware
                if (result.redirect !== undefined){
                  location.href = result.redirect;
                }

                $("#div-download-img").append(result.data);
                document.getElementById('div-download-img').innerHTML = '';
                $('.loading_div_'+id).html('');
            },error:function(error){
                console.log(error);
            }
        });
    });
</script>
@endpush

@endsection
