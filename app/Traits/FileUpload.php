<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\helpers\UtilHelper;

trait FileUpload
{

  public function storeFile( $request , $params =[] )
  {

      // 'fileUpload' => 'image',
      // 'tablename' => User::FILES_TABLE_NAME,
      // 'folder' => User::FILE_FOLDER,
      // 'recordId' => $user->id,
      // 'prifex' => '',
      // 'putDomianToFileName' => '',
      // 'sizes' => [],


      if ($request) {
        $file = $request->file( $params['fileUpload'] );
      } else {
        $file = $params['fileUpload'] ;
      }

      $putDomianToFileName = isset( $params['putDomianToFileName'] ) ? $params['putDomianToFileName'] : null;
      $prifex = isset( $params['prifex'] ) ? $params['prifex'] : null;
      $recordId = $params['recordId'];
      $tableName = isset( $params['tableName'] ) ? $params['tableName'] : null;



      $orginalName = mb_substr( $file->getClientOriginalName() , 1 , 50, 'UTF-8');
      $extension = $file->getClientOriginalExtension();
      if ( $putDomianToFileName === true) { $putDomianToFileName = \Config::get('app.name'); }
      $newFileName =  $recordId . '_' . $orginalName . '_' .  $prifex . '_'  . uniqid() . '_' . $putDomianToFileName . '.' . $extension;
      $newFileName = trim(str_replace(' ','_', convertToLower($newFileName) ));

      try
      {
          $path = $file->storeAs($params['folder'], $newFileName);
      }
      catch (\Exception $e) {
        return false; // $e->getMessage(); //
      }

      return $path;

  }

  public function storeFromBase( $image , $params =[] )
  {

      $putDomianToFileName = isset( $params['putDomianToFileName'] ) ? $params['putDomianToFileName'] : null;
      $prifex = isset( $params['prifex'] ) ? $params['prifex'] : null;
      $recordId = $params['recordId'];
      $tableName = isset( $params['tableName'] ) ? $params['tableName'] : null;


      $imageParts = explode(";base64,", $image);
      $imageTypeAux = explode("image/", $imageParts[0]);
      $imageType = $imageTypeAux[1];
      $imageBase64 = base64_decode($imageParts[1]);



      $newFileName = $params['folder'] . '/' . $recordId . '_' . $prifex . uniqid() . '_' . $putDomianToFileName . '.' . $imageType;
      $newFileName = trim(str_replace(' ','_', convertToLower($newFileName) ));
      $uploadPath = str_replace('lara/public','public_html/storage/app/',public_path());
      $uploadPath =  $uploadPath . $newFileName;

      // $file = $folderPath . uniqid() . '. '.$image_type;
      file_put_contents($uploadPath, $imageBase64);

      return $newFileName;

  }

  public function deleteFiles( $files = [] )
  {
    foreach ($files as $file) {
      Storage::delete($file);
    }
  }


}
