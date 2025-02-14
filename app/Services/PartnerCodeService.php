<?php

namespace App\Services;
use App\PartnerCode;

class PartnerCodeService
{

    public function isCodeExists($code)
    {
        return PartnerCode::where('code', $code)->exists();
    }

    public function isCodeExistsActive($code)
    {
        return PartnerCode::where('code', $code)->active()->exists();
    }

    public function isCodeUsed($code)
    {
        return \App\member::where('partner_code', $code)->exists();
    }


}
