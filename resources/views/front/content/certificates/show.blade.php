<style type="text/css">
    @font-face {
        font-family: "Custom-Font";
        src: url("{{ asset('assets/fonts/lang/'.$data['locale']. ( $data['locale'] == 'am' ? '.ttc' : (in_array($data['locale'],['ar','bn']) ? '.otf' : '.ttf?u') )) }}");
    }
</style>

{!! str_ireplace(['[name]','[degree]','[rate]'],[$data->member->name,round($data['degree'],2),__('trans.rate.'.$data['rate'],[],$data['locale'])],$content) !!}
