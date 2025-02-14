@extends('front.layouts.the-index')
@section('head')

<link rel="preconnect" href="https://www.youtube.com">

<style>
  .for_mob{
    display: none !important;
  }
  @media only screen and (max-width: 767px){
    .hero-area{
      height: 150px !important;
    }
    .for_web{
      display: none !important;

    }
    .for_mob{
      display: block !important;
    }
    .courses-details-header .courses-meta ul li {
    font-size: 14px;
    margin-top: 15px;
    margin-right: 5px !important;
    padding-right: 0px !important;
    padding-left: 0;
    }
    }
    .courses-title {
            text-align: center;
            font-size: x-large;
            font-weight: 900;
        }
        .courses-details-desc .courses-accordion .accordion .accordion-item .accordion-content .courses-lessons .single-lessons .lessons-info .duration {
            text-align: center;
            margin-right: 0;
            direction: ltr;
            margin-left: 10px;
        }
        .courses-details-image img {
            height: 350px;

        }
        .swal2-popup .swal2-select {
            display: none;
          }
          .courses-details-image.text-center {
              height: auto;
          }
          .courses-details-image.text-center iframe {
                height: 360px;
            }
          audio {
              width: 100%;
          }
          i.icon_live {
            color: red !important;
            text-transform: uppercase;
            font-size: 12px !important;
            padding: 3px;
            font-weight: 900;
            border: dashed 2px;
            margin: auto -15px;
        }
        label.lesson_titel {
            color: #5cc9df;
            font-size: 19px;
            font-weight: 900;
            cursor: pointer;
            text-decoration: underline;
        }
        .top_user_img{
          display: block;
          width: 50px;
          height: 50px;
          /* margin: 1em auto; */
          background-size: cover;
          background-repeat: no-repeat;
          background-position: center center;
          -webkit-border-radius: 99em;
          -moz-border-radius: 99em;
          border-radius: 99em;
          border: 5px solid white;
          box-shadow: 0 3px 2px rgba(0, 0, 0, 0.3);
          background-color: #efefef;
        }
        .show_div{ display: block; float: left;}
        .hide_div{ display: none}


        input[type="file"] {
          width: 100%;
          background-color: #5ecc5e;
          padding: 9px;
          border-radius: 7px;
        }


</style>

@endsection

@section('content')

<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <!-- Hero Content -->
                <div class="hero-content text-center row">

                </div>
            </div>
        </div>
    </div>
</section>

<section class="courses-details-area pb-70">
  <div class="container">

    <div class="courses-details-header">
        <div class="row align-items-center">


            <div class="col-lg-12">
                <div class="courses-title" style="color: gray;">رفع الملفات الخاصة باختبار</div>
                <div class="courses-title" style="padding-bottom: 25px;">{{ $course->name }} </div>

                <div class="alert alert-primary" role="alert">
                  {!! __('trans.course_upload_but_title_notes_189') !!}
                </div>


                <div class="col-lg-12">
                  @if($errors->any())
                      <div class="alert alert-danger alert-dismissible">
                          <strong>
                              {!! implode('<br/>', $errors->all('<span>:message</span>')) !!}
                          </strong>
                      </div>
                  @endif
                </div>

                <div class="col-lg-12">
                  @if($errors->any())
                      <div class="alert alert-danger alert-dismissible">
                          <strong>
                              {!! implode('<br/>', $errors->all('<span>:message</span>')) !!}
                          </strong>
                      </div>
                  @endif
                </div>

                <div class="col-lg-12">
                  @if(session()->has('message'))
                      <div class="alert alert-success">
                          {{ session()->get('message') }}
                      </div>
                  @endif
                </div>



                  <form action="{{ route('front.course_tests_visual_upload') }}" method="post" enctype="multipart/form-data" >

                    <div class="d-flex justify-content-center" margin="15px;">
                      <div id="loading" class="" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </div>

                    <div class="row" >
                        @csrf
                        <input type="hidden" name="site_id" value="{{ $site->id }}">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">

                          <div class="col-md-4">
                            <label>1 - ادخل عنون الملف</label>
                            <input type="text" name="title" class="form-control form-control-lg" maxlength="100">
                          </div>
                          <div class="col-md-4">
                            <label>2 - ثم اختر الملف</label><br>
                            <input type="file" name="upload" id="fileUpload">
                            <!-- <button style="display:block;width:120px; height:30px;" onclick="document.getElementById('fileUpload').click()">اختر الملف</button> -->

                          </div>

                          <div class="col-md-3">
                              <label>3 - ثم اضغط</label><br>
                              <button class="btn" onclick="showLoading()"  style="margin: 0px;color: black;background-color: #e9e9ed;border: 1px solid;">رفع الملف</button>
                          </div>

                    </div>
                  </form>



                <div class="col-lg-12" style="text-align: center;padding-top: 15px;">
                  @foreach($courseTestVisual->members_tests_visual_uploads ?? [] as $testUpload)
                      <div class="row" style="padding: 15px;border-bottom: 1px solid #e1dddd;">
                        <div class="col-lg-5">{{ $testUpload->title }}</div>
                        <div class="col-lg-4">{{ $testUpload->created_at->format('Y/m/d') }}</div>
                        <div class="col-lg-3">
                          <form action="{{ route('front.course_tests_visual_delete') }}" method="post">
                            @csrf
                            <input type="hidden" name="test_visual_id" value="{{ $testUpload->course_test_visual_id }}">
                            <input type="hidden" name="test_upload_id" value="{{ $testUpload->id }}">
                            <button class="btn" style="margin: 0px;color: white;background-color: #d73d3d;">حذف</button>
                          </form>
                        </div>
                      </div>
                  @endforeach
                </div>

                <div class="courses-meta">




                </div>
            </div>

        </div>
    </div>

  </div>
</section>


@endsection


@section('script')
<script>
function showLoading()
{
  $("#loading").addClass('spinner-border');
}
</script>


@endsection
