<?php

namespace App\Services;
use DB;

class ExportService
{

  public function exportCsv($columns, $query, $chunkSize = 1000)
  {

        // dd($query->chunk(10, function($rows){
        //   foreach ($rows as &$row) {
        //     dd($row->id);
        //   }
        // }));



        // https://gist.github.com/albofish/c496bfa9183556155a18
        header("Content-type: text/csv; charset=UTF-8");
        header("Content-Encoding: UTF-8");
        header("Content-Disposition: attachment; filename=export-" . date("Y-m-d-H-i-s") . ".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");
        fputcsv($output, $columns);


        if(is_array($query)){ // if we send array ( like results from db::select ) so put the result directly to file
            $rows = $query;
            foreach ($rows as &$row) {
              fputcsv($output, (array) $row);
            }
        } else {
            $query->chunk($chunkSize, function($rows) use(&$output) { // if we send query itself so chunk it first and put ......
              foreach ($rows as &$row) {
                fputcsv($output, (array) $row);
              }
            });
        }

        fclose($output);

  }

}
