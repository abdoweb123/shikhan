@extends ("back/emails.main")

@section ("title")
    <b style="font-family: 'Custom-Font';font-size:24px;"> {{ $subject }} </b>
@stop

@section ("body")

    <style type="text/css">
        @font-face {
            font-family: "Custom-Font";
            src: url("{{ asset('assets/fonts/locale/'.$data['locale']. ( $data['locale'] == 'am' ? '.ttc' : (in_array($data['locale'],['ar','bn']) ? '.otf' : '.ttf?u') )) }}");
        }
    </style>

    {!! str_ireplace(['[name]','[degree]','[rate]'],[$data['name'],round($data['degree'],2),__('trans.rate.'.$data['rate'],[],$data['locale'])],$content) !!}
@stop
