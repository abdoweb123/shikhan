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
        '[degree]',
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
        $user->name_lang,
        $data->user_sites_full_degree,
        __('trans.rate.'.$data->user_sites_full_rate,[],$data->locale),
        __('trans.rate.'.$data->user_sites_full_rate,[],'en'),
        __('trans.passed.'.$user->gender,[],$data->locale),
        $exam_at_hijri,
        $data->created_at,
        'دبلوم متقدم',
        'Advanced Diploma',
        $html
      ],
      $content) !!}
@stop
