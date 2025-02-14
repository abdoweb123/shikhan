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
        $user->getNationality('en'),
        $user->getNationality('en'),

        $user->name_lang ? $user->name_lang : $user->name,

        $certificate_data['sites']->where('locale','sw')->first() ? $certificate_data['sites']->where('locale','sw')->first()->name : '',
        $certificate_data['sites']->where('locale','en')->first() ? $certificate_data['sites']->where('locale','en')->first()->name : '',

        $certificate_data['fullDegree'],
        __('trans.rate.'.$certificate_data['fullRate'],[],$data->locale),
        __('trans.rate.'.$certificate_data['fullRate'],[],'en'),

        __('trans.passed.'.$user->gender,[],app()->getLocale()),
        $exam_at_hijri,
        date('Y-m-d'),
        $user->avatar_path,
        $certificate_data['code'],
        $bg_image
      ],
      $content) !!}

@stop
