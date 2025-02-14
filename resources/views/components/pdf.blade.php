<!DOCTYPE html>
<html>
<head>
  <link href="{{ asset('assets/admin/css/pdf.css') }}" rel="stylesheet" type="text/css" />
<style>

/* .image{width:80px;height:80px;border-radius: 50%;}
th {border: 1px solid #cccccc;padding-top: 5px; padding-bottom: 5px;}
td {padding-left: 10px;padding-right: 10px;}
.td_title { font-size: 16px; width : 200px;}
.td_data { font-size: 16px; font-weight: 600; text-align: right;} */
/*
 .dataTables_wrapper img { max-width: 150px; }
 .dataTables_wrapper .td_title { font-size: 16px; width : 200px;}
 .dataTables_wrapper .td_data { font-size: 16px; font-weight: 600; text-align: right;}
 .dataTables_wrapper #report_title { font-size: 24px; font-weight: 600; text-align: right; padding: 20px 8px;} */

</style>
</head>
<body style="font-family: XB Riyaz;direction:rtl;">
    <table>
      <tr><td>
        <img style="width: 500px !important; height: auto !important;" src="{{ asset('storage/app/'.\App\Models\Setting::where('property','logo')->first()->value) }}">
      </td></tr>
      <tr>
        <td>
          {!!$data!!}
        </td>
      </tr>
    </table>
</body>
</html>
