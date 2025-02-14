@extends('front.layouts.the-index')
@section('content')

<style >
  .form-group {
    display: flex;
  }
  input.form-check-input {
      margin: 4px -10px 0 0;
  }
  .form-group label {
      width: 89% !important;
      padding: 10px 0;
      font-size: revert;
      color: #a97f51;
      font-weight: 600;
  }
  i.fa.fa-whatsapp {
      border-radius: 50%;
  }
  .clock-sticky {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    z-index: 999999;
  }
  .clock-div{
    background-color: white;
    border-radius: 10px;
    box-shadow: 0px 3px 10px #0000002e;padding: 13px 0px;
  }
  .clock-text {
    padding: 16px 7px;
    font-size: 16px;
    font-weight: bold;
    color: gray;
  }
  .clock-total {
    border-radius: 8%;
    text-align: center;
    padding: 10px;
    width: 60px;
    margin: 0px 7px;
    height: 60px;
    font-weight: bold;
    border: 1px solid #f7a3a3;
    box-shadow: 1px 2px 8px #0511402b;
    color: #e66565;
  }
  .clock-unit {
    border-radius: 8%;
    text-align: center;
    padding: 10px;
    width: 60px;
    margin: 0px 7px;
    height: 60px;
    font-weight: bold;
    border: 1px solid #41cc64;
    box-shadow: 1px 2px 8px #0511402b;
    color: #2f9344;;
  }
  .colck-seprate{
    padding: 8px 4px;
    font-size: 23px;
    font-weight: bold;
    color: #34954d;
  }
</style>



<section class="bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <!-- Hero Content -->
                {{--
                <div class="hero-content text-center row">
                    <div class="col-4" style="padding-top: 25px;">
                        <img src="{{ url($course->logo_path) }}" alt="{{ $course->name }}" class="bg-light img-raised img-fluid" style="width: 200px;border-radius: 18px;"> <!-- class="p-3 bg-light img-raised rounded-circle img-fluid" -->
                    </div>
                </div>
                --}}
            </div>
        </div>
    </div>
</section>



<div class="container pt-3">
  <section class="notify">
    @if(!empty($errors))
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                  @foreach($errors as $error)
                    <div class="alert alert-danger text-center">
                        {{ $error }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                  @endforeach
                </div>
            </div>
        </div>
    @endif
  </section>
</div>
@endsection
