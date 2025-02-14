@extends('front.layouts.the-index')
@section('head')
<style>
  .td_title {background-color: #b3b54b38; font-size: 18px;font-weight: bold;text-align: right}
  .td_title_selected {background-color: #b3b54b38; font-size: 25px;color: #89592c;font-weight: bold;text-align: right}

  /* @media screen and (min-width: 0px) and (max-width: 800px) {
    #div_notification_desktop {
      display: none !important;
    }
    #div_notification_mobile {
      display: block;
    }
  }
  @media screen and (min-width: 801px) and (max-width: 1900px) {
    #div_notification_desktop {
      display: block;
      clear: both;
    }
    #div_notification_mobile {
      display: none;
    }
  } */

  @media screen and (min-width: 0px) and (max-width: 2800px) {
    #div_notification_desktop {
      display: none !important;
    }
    #div_notification_mobile {
      display: block;
    }
  }

</style>



<style>
  ul.timeline {
      list-style-type: none;
      position: relative;
  }
  ul.timeline:before {
      content: ' ';
      background: #d4d9df;
      display: inline-block;
      position: absolute;
      right: 29px;
      width: 2px;
      height: 100%;
      z-index: 400;
  }
  ul.timeline > li {
      margin: 20px 0;
      padding-left: 20px;
  }
  ul.timeline > li:before {
      content: ' ';
      background: white;
      display: inline-block;
      position: absolute;
      border-radius: 50%;
      border: 3px solid #3aa174;
      right: 20px;
      width: 20px;
      height: 20px;
      z-index: 400;
  }
</style>



@endsection
@section('content')


<section class="bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12" >
                <!-- Hero Content -->
                <div class=" text-center row">
                    <div class="col-2">
                        @if(!empty(Auth::guard('web')->user()->avatar))
                            <div class="avatar">
                                {{--dd(url(Auth::guard('web')->user()->avatar))--}}
                                <img src="{{ url(Auth::guard('web')->user()->avatar_path) }}" class="bg-light img-raised img-fluid" style="width: 80px;border-radius: 18px;" alt="{{ Auth::guard('web')->user()->name }}">
                            </div>
                        @else
                            <div class="p-5"></div>
                        @endif
                    </div>
                    <div class="col-10 text-left">
                        <h1 class="sec-color">@lang('meta.title.notifications_inner')</h1>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>



  <div class="container mt-5 mb-5">
  	<div class="row justify-content-center">
  		<div class="col-md-9">
  			<!-- <h4>الإشعارات</h4> -->
  			<ul class="timeline" style="text-align: right;">
          @foreach ($allNotifications as $notification)
    				<li style="border-bottom: 1px solid #dfdfdf;padding-bottom: 10px;">

                @php $params = [ 'id' => $notification->id, 'details_only' => true ]; @endphp
                <a type="button"  onclick='getNotificationDetails( @json($params) )'
                    data-toggle="modal" data-target="#notificationInnerModal" style="padding-right: 10px;font-weight: bold;">

                  <!-- <i class="far fa-bell" style="color: #c1c1c1;"></i> -->
                  @if ($notification->seen_at)
                    <i class="far fa-eye" style="color: #3aa174;"></i>
                  @else
                    <i class="far fa-eye-slash" style="color: #c14d4d;"></i>
                  @endif

                  {{ $notification->title }}
                </a>

              @php
                $notification_hijri = new \App\helpers\HijriDateHelper( strtotime($notification->created_at) );
                $notification_hijri = $notification_hijri->get_year() . '-' . $notification_hijri->get_month() . '-' . $notification_hijri->get_day();
              @endphp
    					<a href="#" class="float-left" style="color: #00532d;">
                <i class="far fa-clock" style="color: #c1c1c1;"></i> {{ $notification_hijri }} هـ    {{ \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d') }} م
              </a>

    					<p>
                @if( Auth::id() == 5972)
                  <form method="post" action="{{ route('send_notifications.inner.delete') }}">
                    <hidden name="notification_id" value="{{ $notification->id }}">
                    <button type="submit" class="btn btn-danger" style="padding: 5px 30px;">حذف</button>
                  </form>
                @endif
              </p>

    				</li>
          @endforeach
  			</ul>
  		</div>
  	</div>
  </div>






<div class="container">
    <div class="description text-center">

    </div>

      {{--
      <!-- 01 desktop show details beside the notification -->
      <div id="div_notification_desktop" style="display: flex;" class="row justify-content-center">
        <div class="col-lg-4 col-md-4">
          <table class="table table-striped mt-3" style="table-layout: fixed;border: 1px solid #d5c2b0;">
            <thead>
              <tr>
                <th scope="col" class="prim-color-light sec-back-color-dark" style="font-size: 20px;text-align: right;">الاشعارات</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($allNotifications as $notification)
                  <tr style="line-break: anywhere;white-space: nowrap;"  id="notification_{{ $notification->id }}">
                    <td class="td_title" id="td_title_{{$notification->id}}" @if( isset($currentNotification) ) @endif >
                        <i class="far fa-bell" style="color: #c1c1c1;"></i>
                        @if ($notification->seen_at) <i class="far fa-eye" style="color: #c1c1c1;"></i> @else <i class="far fa-eye-slash"></i> @endif
                        @php $params = [ 'id' => $notification->id, 'details_only' => true ]; @endphp
                        <input type="button"  value="{{ $notification->title }}" onclick='getNotificationDetails( @json($params) )'
                          style="font-weight: bold; padding: 5px 10px; border: none;background-color: #f0f8ff00;max-width: 80%;">
                        <br>
                        @php
                          $notification_hijri = new \App\helpers\HijriDateHelper( strtotime($notification->created_at) );
                          $notification_hijri = $notification_hijri->get_year() . '-' . $notification_hijri->get_month() . '-' . $notification_hijri->get_day();
                        @endphp
                        <div style="font-size: 15px;color: gray; padding: 3px 0px;display: flex;">
                            <span style="padding: 6px 0px;">
                              <i class="far fa-clock" style="color: #c1c1c1;"></i>
                              {{ $notification_hijri }} - {{ \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d') }}
                            </span>
                            @if( Auth::id() == 5972)
                            <div style="width: 100%;text-align: left;padding-left: 6px;">
                              <form onsubmit="deleteNotification(event, this, {{$notification->id}})" method="post" action="{{ route('send_notifications.inner.delete') }}">
                                @csrf
                                <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                <button type="submit" class="btn btn-danger">حذف</button>
                              </form>
                            </div>
                            @endif
                        </div>
                    </td>
                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="col-lg-6 col-md-6">
          <div id="notification_details_desktop" style="padding: 55px 20px;text-align: center;">
            @isset($currentNotification) {!! $currentNotification->body !!} @endisset
          </div>
        </div>
      </div>
      --}}






      {{--
      <!-- 02 mobile show details in modal window -->
      <div id="div_notification_mobile" class="row justify-content-center">
        <div class="col-lg-12">
          <table class="table table-striped table-responsive mt-3" style="border: 1px solid #d5c2b0;">
            <thead>
              <tr>
                <th scope="col" style="background-color: #b57f4b;font-size: 20px;text-align: right;color: #e9d293;">الاشعارات</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($allNotifications as $notification)
                  <tr id="notification_{{ $notification->id }}">
                    <td class="td_title" id="td_title_{{$notification->id}}" @if( isset($currentNotification) ) @endif>
                        <i class="far fa-bell" style="color: #c1c1c1;"></i>
                        @if ($notification->seen_at) <i class="far fa-eye" style="color: #c1c1c1;"></i> @else <i class="far fa-eye-slash"></i> @endif
                        @php $params = [ 'id' => $notification->id, 'details_only' => true ]; @endphp
                        <button type="button"  onclick='getNotificationDetails( @json($params) )'
                          data-toggle="modal" data-target="#notificationInnerModal" style="font-weight: bold; padding: 5px 10px; border: none;background-color: #f0f8ff00;">{{ $notification->title }}</button>
                        <br>
                        @php
                          $notification_hijri = new \App\helpers\HijriDateHelper( strtotime($notification->created_at) );
                          $notification_hijri = $notification_hijri->get_year() . '-' . $notification_hijri->get_month() . '-' . $notification_hijri->get_day();
                        @endphp
                        <div style="font-size: 15px;color: gray; padding: 3px 0px;display: flex;">
                            <span style="padding: 6px 0px;">
                              <i class="far fa-clock" style="color: #c1c1c1;"></i>
                              {{ $notification_hijri }} - {{ \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d') }}
                            </span>
                            @if( Auth::id() == 5972)
                            <div style="width: 100%;text-align: left;padding-left: 6px;">
                              <form method="post" action="{{ route('send_notifications.inner.delete') }}">
                                <hidden name="notification_id" value="{{ $notification->id }}">
                                <button type="submit" class="btn btn-danger">حذف</button>
                              </form>
                            </div>
                            @endif
                        </div>
                    </td>
                  </tr>
              @endforeach
            </tbody>
          </table>
      </div>
      --}}







        <div class="modal fade" id="notificationInnerModal" tabindex="-1" role="dialog" aria-labelledby="notificationInnerModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <!-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div id="notification_details_mobile" class="modal-body">
                <p>@isset($currentNotification) {!! $currentNotification->body !!} @endisset</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
              </div>
            </div>
          </div>
        </div>

      </div>

      <br><br>

    </div>
@endsection




@section('script')

@if(isset($currentNotification))
  <script>
    if ( $('#div_notification_mobile').css('display') == 'flex' ){
      $('#notificationInnerModal').modal();
    }
  </script>
@endif

<script>
  function getNotificationDetails( $params = [] ) {

    var elm_desktop = $('#notification_details_desktop');
    var elm_mobile = $('#notification_details_mobile');

    $(".td_title").removeClass("td_title_selected");
    $("#td_title_"+$params['id']).addClass("td_title_selected");

    $.ajax({
        url: "{{ route('notifications_inner_index') }}",
        type: "get",
        data : { 'params': $params },
        success: function (data) {
             // console.log(data['data']);

             $notification = data['data']['body'].replace('nl-container', 'nl-container table-responsive');

             elm_desktop.html($notification);
             elm_mobile.html($notification);

         },error:function(data){
             console.log(data.responseJSON);
         }
     });
  };
</script>

<script>
  function deleteNotification(e,me,id) {
    e.preventDefault();

    $.ajax({
        url: $(me).attr("action"),
        type: $(me).attr("method"),
        data : $(me).serialize(),
        success: function (data) {
            // console.log(data);
            if(data['status'] == 1){
              $('#notification_'+id).hide();
            }
         },error:function(data){
             console.log(data);
         }
     });
  };
</script>


<script>
  $(document).ready(function() {
      var element = document.getElementsByClassName("nl-container");
      element.classList.add("table-responsive");
  });
</script>


@endsection
