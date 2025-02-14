@extends('front.layouts.the-index')
@section('head')

<style>
    .single-courses-item .courses-image {
        height: 230px;
        overflow: hidden;
    }
    span.sub-dip a {
        color: white;
    }
    span.sub-dip:hover a {
        color: #218838;
    }
    span.sub-dip:hover  {
        background-color: #ffffff;
            box-shadow: 1px 2px 9px 1px #1e7e34;
        border-color: #1e7e34;
    }
    .link-from-here {
      color: #218838;
      font-size: x-large;
    }
    .link-from-here:hover {
      color: #fdfdfd;
      font-size: x-large;
      text-shadow: 0px -2px 4px #28a745;
  }
  .show_div{ display: block; float: left;}
  .hide_div{ display: none}
</style>

@endsection


@section('content')
<section class=" bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
  <div class="container h-100">
    <div class="row  align-items-center">
      <div class="col-12" >
        <div class="hero-content text-center row">
          <div class="col-md-12 justify-content-center" style="text-align: center;display: flex;">
            <img src="" class="bg-light img-raised img-fluid" style="width: 100px;border-radius: 18px;">
            <h1 style="color: #23524f;font-size: 27px;padding: 15px;">الشهادات</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div id="div-download-img"></div>

@if (isset($courseTestResult))
  @if ($courseTestResult)
    <div class="profile-content">
      <div class="container" style="padding: 0px 20px 0px 20px;">
        <div class="row">
          <a data-href="{{ route('download-certificate', ['id' => $courseTestResult->id.'-'.$courseTestResult->site_id, 'type' => 'jpg']) }}"
            class="download_image btn but-default">
            <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;صورة&nbsp;</i>
          </a>
          <a data-href="{{ route('download-certificate', ['id' => $courseTestResult->id.'-'.$courseTestResult->site_id, 'type' => 'pdf']) }}"
            class="download_image btn but-default">
            <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;بى دى اف&nbsp;</i>
          </a>
        </div>
      </div>
    </div>
  @endif
@endif


@endsection






@section('script')
<script type='text/javascript'>
  $('.download_image').click(function(){
    document.getElementById('div-download-img').innerHTML = '';
    var url = $(this).attr('data-href') ;
    $.ajax({
        url: url,
        type: "GET",
        data:{},
        success: function(result){
            $("#div-download-img").append(result.data);
            document.getElementById('div-download-img').innerHTML = '';
        },error:function(error){
            console.log(error);
        }
    });
  });
</script>
@endsection
