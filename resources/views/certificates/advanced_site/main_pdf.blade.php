@extends ("certificates.pdf.site_layout_pdf")

@section ("title")
@stop

@section ("body")

    {!! str_ireplace([
        '[name]',
        '[name_lang]',

        '[site_certificate_code]',

        '[user_id_no]',
        '[user_birthdate]',
        '[user_birthdate_ltr]',
        '[user_nationality]',
        '[user_nationality_lang]',

        '[count_user_successed_sites]',
        '[sum_sites_valid_courses]',
        '[sum_sites_videos_duration]',

        '[sites_count]',
        '[sites_count_lang]',
        '[courses_count]',
        '[courses_count_lang]',
        '[videos_duration_count]',
        '[videos_duration_count_lang]',


        '[degree]',
        '[rate]',
        '[rate_lang]',

        '[gender-passed]',

        '[exam_date_h]',
        '[exam_date_m]',
        '[user_photo]',
        '[bg_imge]'
      ],
      [
        $user->name,
        $user->name_lang,

        $data->certificate_code,

        $user->id_number,
        $user->birthday,
        $user->birthdayLtr,
        $user->getNationality(),
        $user->getNationality('en'),

        $data->countUserSuccessedSites ,
        $data->sumSitesValidCourses,
        $data->sumSitesVideosDuration,

        ' دبلوم ',
        ' Diplomas ',
        ' دورة ',
        ' courses ',
        ' ساعة ',
        ' Hours ',


        $data->user_sites_full_degree,
        __('trans.rate.'.$data->user_sites_full_rate,[],$data->locale),
        __('trans.rate.'.$data->user_sites_full_rate,[],'en'),

        __('trans.passed.'.$user->gender,[],app()->getLocale()),

        $exam_at_hijri,
        \Carbon\Carbon::parse($data->created_at)->format('Y-m-d'),
        $user->avatar_path,
        $bg_image
      ],
      $content) !!}

@stop
