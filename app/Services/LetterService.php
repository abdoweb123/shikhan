<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use App\Models\Letter;

class LetterService
{
    public function getLetters()
    {
        return Letter::orderby('sort')->get();
    }

    // public function getLetterOfId( $id )
    // {
    //     return Letter::where( 'id' , $id )->first();
    // }
    //
    // public function getLetterOfName( $name )
    // {
    //     return Letter::where( 'name' , $name )->first();
    // }
}
