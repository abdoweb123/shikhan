@extends ("certificates.layout_pdf")

@section ("title")
    <b style="font-family: 'arial';font-size:24px;"> {{ $subject }} </b>
@stop

@section ("body")

    {!! str_ireplace([
        '[name]',
        '[rate_word]',
        '[degree]',
        '[rate]',
        '[gender-passed]',
        '[exam_date_h]',
        '[exam_date_m]',
        '[course_name]',
        '[site_name]',
        '[test_code]',
        '[bg_imge]'
      ],
      [
        $data->member->name,
        __('trans.rate_word'),
        round($data['degree'],2),
        __('trans.rate.'.$data['rate'],[],$data['locale']),
        __('trans.passed.'.$data->member->gender,[],$data['lang']),
        $exam_at_hijri,
        \Carbon\Carbon::parse($data->created_at)->format('Y-m-d'),
        $course->title,
        $termData->name,
        $data->code,
        $bg_image
      ],
      $content) !!}
@stop
