@extends ("emails.main")

@section ("title")
    <b style="font-family: 'Custom-Font';font-size:24px;"> {{-- $subject --}} </b>
@stop

@section ("body")

    <style type="text/css">
        @font-face {
            font-family: "Custom-Font";
            src: url("{{ asset('assets/fonts/lang/'.$data['locale']. ( $data['locale'] == 'am' ? '.ttc' : (in_array($data['locale'],['ar','bn']) ? '.otf' : '.ttf?u') )) }}");
        }
    </style>


    {!! __('core.send_user_password_body' , [ 'b' => '<br>' , 'name' => $data->name ]) !!}
    <br>
    https://courses.fadamedia.com/ar/login
    <br>
    email : {{ $data->email }}
    <br>
    password : {{ $data->ps }}


@stop
