<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\language;

class TranslationController extends Controller
{

    private $files = ['trans','auth','core','dates','email','words','field'];

    public function __construct()
    {
        View::share('files', $this->files);
    }

    public function index()
    {
        return redirect()->route('dashboard.translations.edit', [
            'translation' => $this->getDefaultLanguage()->alies, 'file' => $this->getDefaultFile()
        ]);
        return view('back.content.translations.index');
    }

    public function edit(Request $request)
    {
        $file = $request->file;

        if (! $file){
          return redirect()->route('dashboard.translations.edit', [
              'translation' => $request->translation,
              'file' => $this->getDefaultFile()
          ]);
        }

        $data = __($request->translation.'/'.$file, [], '/');
        return view('back.content.translations.index', ['data' => $data]);

    }

    public function update(Request $request)
    {

      // dd($request->data);
      // dd($request->query('file'));

      $data = $request->data;

      $dataAsString = (string) json_encode($data, JSON_UNESCAPED_UNICODE) ;
      $dataAsString= str_replace(',',','."\r\n", $dataAsString);
      $dataAsString= str_replace(':','=>', $dataAsString);
      $dataAsString= str_replace('{','<?php return [',$dataAsString);
      $dataAsString= str_replace('}','];',$dataAsString);

      $path = resource_path('lang/'.$request->translation.'/'.$request->query('file').'.php');


      file_put_contents($path, $dataAsString);

      return redirect()->route('dashboard.translations.edit', ['translation' => $request->translation])->with('success', 'updated Successfully!');

    }


    public function create()
    {


    }

    public function store(Request $request)
    {


    }

    public function convertToCsv(Request $request)
    {
        $file = $request->query('file');
        $data = __($request->translation.'/'.$request->lang.'/'.$file, [], '/');

        header("Content-type: text/csv; charset=UTF-8");
        header("Content-Encoding: UTF-8");
        header("Content-Disposition: attachment; filename=export-" . date("Y-m-d-H-i-s") . ".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");
        fputcsv($output, ['key', 'value']);


        foreach ($data as $key => $value) {
          fputcsv($output, [$key,$value] );
        }

        fclose($output);

    }


    public function getDefaultLanguage()
    {
        return getActiveLanguages()->first();
    }

    public function getDefaultFile()
    {
        return $this->files[0];
    }









}
