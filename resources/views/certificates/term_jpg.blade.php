@extends ("certificates.layout_img")

@section ("title")
@stop

@section ("body")

    {!! str_ireplace([
        '[name]',

        '[user_id_no]',
        '[user_birthdate]',
        '[user_birthdate_ltr]',
        '[user_nationality]',
        '[user_nationality_lang]',

        '[name_lang]',
        '[site_name]',
        '[site_name_lang]',
        '[degree]',
        '[rate]',
        '[rate_lang]',
        '[gender-passed]',
        '[exam_date_h]',
        '[exam_date_m]',
        '[user_photo]',
        '[site_certificate_code]',
        '[bg_imge]'
      ],
      [
        $user->name,

        $user->id_number,
        $user->birthday,
        $user->birthdayLtr,
        $user->getNationality(),
        $user->getNationality('en'),

        $user->name_lang,
        $data->title,
        $data->title_lang,
        $data->user_term_degree,
        __('trans.rate.'.$data->user_term_rate,[],$data->locale),
        __('trans.rate.'.$data->user_term_rate,[],'en'),
        __('trans.passed.'.$user->gender,[],app()->getLocale()),
        $exam_at_hijri,
        \Carbon\Carbon::parse($data->created_at)->format('Y-m-d'),
        $user->avatar_path,
        $data->termCertificateCode,
        $bg_image
      ],
      $content) !!}

@stop
