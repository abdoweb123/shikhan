<!-- for landing page ( route landing.index ) -->
<!-- if user enter site from face adv to landing1 we put a flag in session to display -->
<!-- register page but without the header menu -->

@php $extend = 'new'; @endphp

@if (Session::has('landing'))
  @php $extend = 'landing1'; @endphp
  {{ Session::forget('landing') }}
@endif
@extends('front.layouts.'.$extend)
<!-- //////////////// -->


@section('head')
    <!-- Styles -->
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
    <link rel="stylesheet" href="{{ asset('assets/front/style_rtl.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('assets/front/style.css') }}">
    @endif

    <!-- select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
 <style>
         .header-area {
            position: absolute !important;
         }
        .section-padding-100-0 {
            padding-top: 145px;
            padding-bottom: 0;
        }
        .register-now .register-contact-form .forms .form-control {
             color: rgb(0 0 0) !important;
        }
        select#country {
            display: block !important;
        }
        .nice-select.form-control {
    display: none !important;
  }
  input#code_country {
      direction: ltr;
      padding: 5px;
  }
  .ul-taps{
    width: 100%;
    display: inline-flex;
  }
  .ul-taps li{

    text-align: center;
    width: 50%;
  }
      .ul-taps li h4{
        margin-bottom: 15px !important;
          margin-top: -18px !important;
          border: 2px solid #e8bb8f;
          box-shadow: 1px 2px 12px 0px #eabe92;
          padding: 10px;
          border-radius: 5px;
          color: #00c1e8;
      }
      h4.unactive {
          background-color: #d89a5f;
          color: #fff !important;
      }
    h4.unactive:hover {
        background-color: #fff;
        color: #d89a5f !important;
    }
    button.btn.clever-btn.w-100 {
    color: #fff;
    background: #2266ae;
  }
  ul.ul-taps {
      margin-bottom: 25px;
  }
  @media only screen and (max-width: 767px){
    .register-now .register-now-countdown {
        display: none !important;
      }
    .register-now .register-contact-form {
        margin-top: 45px !important;
        padding: 6px !important;
    }
    .ul-taps li h4 {
        font-size: 18px !important;
        padding: 4px;
        margin: 7px 2px !important;
        font-weight: 900;
    }
  }
 </style>
@endsection
@section('content')




<form method="POST" class="row justify-content-center" action="{{ route('t2') }}">

  <div class="register-contact-form mb-50" style="flex: 0 0 60%;">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12" style="text-align: center;">
          <div class="forms">
                  @csrf

                  <div class="row">

                    <div class="col-12" style="text-align: right;">
                      <select class="diplome_ids_01" name="diplome_ids_1[]" multiple="multiple">
                        <option value="1">a1</option>
                        <option value="2">a2</option>
                        <option value="3">a3</option>
                        <option value="4">a4</option>
                        <option value="5">a5</option>
                      </select>
                    </div>

                    <div class="col-12" style="text-align: right;">
                      <select class="diplome_ids_02" name="diplome_ids_2[]" multiple="multiple">
                        <option value="6">a1</option>
                        <option value="7">a2</option>
                        <option value="8">a3</option>
                        <option value="9">a4</option>
                        <option value="10">a5</option>
                      </select>
                    </div>


                      <div class="col-12" style="text-align: center;">
                          <button type="submit" class="btn clever-btn w-100">
                              اشترك{{-- __('core.register') --}}
                          </button>
                      </div>


                  </div>


          </div>
        </div>
      </div>
    </div>
  </div>





</form>





<div>






</div>

</section>

@endsection
@section('script')
<!-- select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
      $('.diplome_ids_01').select2({
        placeholder: 'اختر'
      });

      $('.diplome_ids_02').select2({
        placeholder: 'اختر'
      });
  });
</script>

<script>
$( "#country" )
  .change(function() {
    var str = "";
    $( "#country option:selected" ).each(function() {
      str = '+' + $( this ).attr('attr-code') ;
    });
    if(str !='+undefined'){

      $('input[name=code_country]#code_country').attr('value',str);

    }


  })
  .trigger( "change" );
</script>


<script>

$("#name").blur(function() {

    $("#validate_error_div").attr("style", "display:none");
    $("#validate_error_div").html( '' );

    let _name = $(this).val();
    if (_name.length < 10){
      $("#validate_error_div").attr("style", "display:block");
      $("#validate_error_div").html( '  الاسم على الاقل' + " {{config('project.max_user_name_chr')}} " + ' حرف ' );

      // return;
    }

    let _token   = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
     url: "{{ route('validate_name') }}",
     type:"POST",
     data:{ name: _name, _token: _token},
     success:function(response){
       // console.log(response);
     },
     error: function(error) {
         var response = JSON.parse(error.responseText);
         // var errorString = '<ul>';
         // $.each( response.errors, function( key, value) {
         //     errorString += '<li>' + value + '</li>';
         // });
         errorString = '';
         $.each( response.errors, function( key, value) {
             errorString +=  value + ' ';
         });
         $("#validate_error_div").attr("style", "display:block");
         $("#validate_error_div").html( $("#validate_error_div").html() + ' , ' + errorString );
         // console.log(errorString);
     }
    });
});
</script>

@endsection
