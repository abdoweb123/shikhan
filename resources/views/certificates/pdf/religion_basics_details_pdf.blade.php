@extends ("certificates.pdf.site_course_layout_pdf")
{{--
@section ("title")
    <b style="font-family: 'arial';font-size:24px;"> {{ $subject }} </b>
@stop
--}}

@section ("body")

    {!! str_ireplace([
        '[bg_image]',
        '[name]',
        '[name_lang]',

        '[rate]',
        '[rate_lang]',
        '[gender-passed]',
        '[exam_date_h]',
        '[exam_date_m]',
        '[site_name]',
        '[site_name_lang]',
        '[html]'
      ],
      [
        $bg_image,
        $user->name,
        $user->name_lang ? $user->name_lang : $user->name,

        __('trans.rate.'.$certificate_data['fullRate'],[],$data->locale),
        __('trans.rate.'.$certificate_data['fullRate'],[],'en'),
        __('trans.passed.'.$user->gender,[],$data->locale),
        $exam_at_hijri,
        date('Y-m-d'),
        $certificate_data['sites']->where('locale', 'sw')->first()->name,
        $certificate_data['sites']->where('locale', 'en')->first()->name,
        $certificate_data['html']
      ],
      $content) !!}
@stop
