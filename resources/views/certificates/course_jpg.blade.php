@extends ("certificates.layout_img")

@section ("title")
    <b style="font-family: 'arial';font-size:24px;"> {{ $subject }} </b>
@stop

@section ("body")

    {!! str_ireplace([
        '[name]',
        '[degree]',
        '[rate]',
        '[gender-passed]',
        '[exam_date_h]',
        '[exam_date_m]',
        '[course_name]',
        '[site_name]'
      ],
      [
        $data->member->name,
        round($data['degree'],2),
        __('trans.rate.'.$data['rate'],[],$data['locale']),
        __('trans.passed.'.$data->member->gender,[],$data['lang']),
        $exam_at_hijri,
        \Carbon\Carbon::parse($data->created_at)->format('Y-m-d'),
        $course->title,
        $site->title
      ],
      $content) !!}
@stop
