@extends ("certificates.pdf.site_course_layout_pdf")
{{--
@section ("title")
    <b style="font-family: 'arial';font-size:24px;"> {{ $subject }} </b>
@stop
--}}

@section ("body")

    {!! str_ireplace([
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
        $user->name,
        $user->name_lang ? $user->name_lang : $user->name,
        $site->user_site_degree,
        __('trans.rate.'.$site->user_site_rate,[],$language),
        __('trans.rate.'.$site->user_site_rate,[],'en'),
        __('trans.passed.'.$user->gender,[],$language),
        $exam_at_hijri,
        $currentDate,
        $site->title,
        $site->title_lang,
        $html
      ],
      $content) !!}
@stop
