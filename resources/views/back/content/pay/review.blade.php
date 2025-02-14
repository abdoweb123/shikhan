@extends('back/layouts.app')
@section('back_css')
    <x-admin.datatable.header-css/>
    <style>

        a.kt-userpic.kt-userpic--circle.kt-margin-r-5.kt-margin-t-5 img {
        width: 50px;
    }
        div#action_div {
        display: inline-flex;
    }
    a.btn.btn-brand.btn-icon-sm {
        color: white;
        background: #0a7a18;
    }
    </style>
@endsection

@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="kt-portlet">

    <div class="kt-portlet__head">
      <div class="kt-portlet__head-label">
        <h3 class="kt-portlet__head-title">
          <div class="row" style="display: inline-flex;">

          </div>
        </h3>

      </div>
    </div>


    <!--begin::Form-->
    <!--end::Form-->
  </div>

  <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">

      <style> .dataTables_wrapper div.dataTables_filter { display: contents; } </style>
      @if(session()->has('success'))
          <div class="alert alert-success text-center">
              {{ session()->get('success') }}
          </div>
      @elseif(session()->has('MasterErorr'))
          <div class="alert alert-danger text-center">
              <strong> Failed!  </strong> {{ session()->get('MasterErorr') }}
          </div>
      @endif

      @include('back.includes.breadcrumb',['routes' => [
          ['name' => __('meta.title.members')],
      ]])

      <div class="col-md-12 row">
          <form action="{{ route('dashboard.pay.review') }}" method="get">
          <div class="col-md-3">

                      <input type="text" name="term" value="{{ @$get['term'] }}" class="form-control" placeholder="{{ __('core.term_p') }}">

          </div>
          <div class="col-md-2">
              @include('back.includes.free_status_select', ['name' => 'search_free_status','select_none' => true])
            </div>
            <div class="col-md-3">
                @include('back.includes.partners_select', ['name' => 'search_partner_id','select_none' => true])
            </div>
          <div class="col-md-2">
             <select name="orderby" class=" form-control col-md-12">
                    <option {{ old('orderby') == 1 ? 'selected' : '' }} value="1">ترتيب بالاحدث اشتراكا</option>
                    <option {{ old('orderby') == 2 ? 'selected' : '' }} value="2">ترتيب بالاقدم اشتراكا</optio     >
                   <option {{ old('orderby') == 3 ? 'selected' : '' }} value="3">ترتيب بالاسم</option>
            </select>

            </div>
            <div class="col-md-1">
                <button class="btn btn-default" type="submit">
                              <i class="glyphicon glyphicon-search"></i>
                          </button>
                </div>
          </form>
      </div>

      <hr>

      <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
        <thead>
            <tr>
                  <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                  <th class="text-center">#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Pay Image</th>
                  <th>Pay Amount</th>
                  <th>Discount</th>
                  <th>Pay Status</th>
                  <th>created at</th>
                  <th style="text-align: center"> @lang('core.actions') </th>
              </tr>
            </thead>
            <tbody>
              @php $langs = ''; @endphp
              @foreach($result as $row)

                <tr id="{{ $row->id }}">
                      <td value="{{ $row->id }}"></td>
                      <td class="text-center"> {{ $row->id }} </td>
                      <td> <a href="mailto:{{ $row->name }}"> {{ $row->name }} </a> </td>
                      <td> <a href="mailto:{{ $row->email }}"> {{ $row->email }} </a> </td>
                      <td> <a href="tel:{{ $row->phone }}"> {{ $row->phone }} </a> </td>


                      <td>
                        @if ($row->pay_image_path)
                          <img src="{{ url($row->pay_image_path) }}" class="img-circle" onclick="enlargeImage({{ $row->id }})" width="30px" height="30px" style="cursor: pointer;" alt="">
                        @endif
                      </td>
                      <td> <a href="tel:{{ $row->phone }}"> {{ $row->amount }} </a> </td>
                      <td> <a href="tel:{{ $row->phone }}"> {{ $row->disccount }} </a> </td>
                      <td> <a href="tel:{{ $row->phone }}"> {{ $row->freeStatusTitle() }} </a> </td>
                      <td> <a href="tel:{{ $row->phone }}"> {{ $row->created_at }} </a> </td>
                      <td></td>



                      <td style="text-align: center;display: flex;">
                        <form action="{{ route('dashboard.pay.review.update') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $row->id }}">
                            @include('back.includes.free_status_select')

                            {{--
                            @foreach($partners as $partner)
                                <option {{ old('partner_id', $row->partner_id) == $partner->id ? 'selected' : '' }} value="{{$partner->id}}"> {{ $partner->name }} </option>
                            @endforeach
                            --}}
                            @include('back.includes.partners_select', ['select_none' => true])

                        <div class="col-lg-12">

                                <input type="number" step="0.01" value="{{ old('pay_amount', $row->pay_amount) }}" class="form-control @error('amount') is-invalid @enderror" name="pay_amount" style="color: black" maxlength="10" placeholder="{{ __('trans.amount') }}">
                                @error('pay_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            <select name="currency_id" class=" form-control col-md-12">
                                @foreach($currencies as $currency)
                                    <option {{ old('currency_id', $row->currency_id) == $currency->id ? 'selected' : '' }} value="{{$currency->id}}"> {{ $currency->name }} {{ $currency->code }} {{ $currency->symbol }}</option>
                                @endforeach
                            </select>
                            <x-buttons.but_submit/>
                        </div>


                        </form>
                        @if ($errors->has('free_status'))
                            <span class="help-block">{{ $errors->first('free_status') }}</span>
                        @endif

                      </td>

                  </tr>
              @endforeach
            </tbody>
      </table>
      @if ($result->isNotEmpty())
      {!! $result->links()!!}
      @endif




      <style>
        .modal {
          display: none; /* Hidden by default */
          position: fixed; /* Stay in place */
          z-index: 1; /* Sit on top */
          padding-top: 50px; /* Location of the box */
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          overflow: auto; /* Enable scroll if needed */
          background-color: rgb(0,0,0); /* Fallback color */
          background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
          overflow: visible;
        }

        .modal-content {
          margin: auto;
          text-align: center;
        }

        .modal-image {
          display: inline-block;
        }

      </style>




      <div id="image_modal" class="modal" style="z-index: 999;"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-content">
          <a  class="btn btn-secondary" onclick="hideModal()">Close</a>

          <div class="modal-body">
            <img src="" id="modal-image" style="width:500px; height:500px;" />
          </div>

        </div>
      </div>







    </div>
  </div>
</div>





@endsection








@section('js_pagelevel')
<x-admin.datatable.footer-js/>

<x-buttons.but_delete_inline_js/>


<script>
function submitForm(me)
{
$(me).closest("form").submit();
}
</script>

<script>
  function enlargeImage(id)
  {
    var imageBox1 = document.getElementById(id);

    // Get the modal image tag
    var modal = document.getElementById("image_modal");

    var modalImage = document.getElementById("modal-image");

    // When the user clicks the big picture, set the image and open the modal
    imageBox1.onclick = function (e) {
      var src = e.srcElement.src;
      modal.style.display = "block";
      modalImage.src = src;
    };
  }


  function hideModal()
  {
    $("#image_modal").hide();
  }


</script>

@endsection
