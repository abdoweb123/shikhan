<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class download extends Controller
{
    public function downloadCertificate(Request $request)
    {
        $file = storage_path('app/public/public/certificates/').$request->file;
        if (!file_exists( $file )) {
            abort(404,'File Not Found');
        }
        return response()->download($file);
    }

}
