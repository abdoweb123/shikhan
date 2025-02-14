@extends ("certificates.layout_pdf_portrait")
{{--
@section ("title")
    <b style="font-family: 'arial';font-size:24px;"> {{ $subject ?? '' }} </b>
@stop
--}}

@section ("body")

    {!! str_ireplace([
        '[name]',
        '[exam_date_h]',
        '[exam_date_m]',
        '[bg_imge]'
      ],
      [
        auth()->user()->name,
        $exam_at_hijri,
        $current_date,
        $bg_image
      ],
      $content) !!}
@stop
