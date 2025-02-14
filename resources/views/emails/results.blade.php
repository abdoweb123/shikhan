@extends ("emails.main")

@section ("title")
    {{--<b style="font-family: 'Custom-Font';font-size:24px;"> {{ $subject }} </b>--}}
    <b style="font-family: 'arial';font-size:24px;"> {{ $subject }} </b>
@stop

@section ("body")

    {{--
    <style type="text/css">
        @font-face {
            font-family: "Custom-Font";
            src: url("{{ asset('assets/fonts/lang/'.$data['locale']. ( $data['locale'] == 'am' ? '.ttc' : (in_array($data['locale'],['ar','bn']) ? '.otf' : '.ttf?u') )) }}");
        }
    </style>
    --}}


    {!! str_ireplace([
        '[name]',
        '[degree]',
        '[rate]',
        '[gender-passed]',
        '[exam_date_h]',
        '[exam_date_m]'
      ],
      [
        $data->member->name,
        round($data['degree'],2),
        __('trans.rate.'.$data['rate'],[],$data['locale']),
        __('trans.passed.'.$data->member->gender,[],$data['lang']),
        $exam_at_hijri,
        \Carbon\Carbon::parse($data->created_at)->format('Y-m-d')
      ],
      $content) !!}
@stop
