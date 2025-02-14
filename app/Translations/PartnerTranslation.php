<?php

namespace App\Translations;
use Illuminate\Database\Eloquent\Model;

class PartnerTranslation extends Model
{
    protected $table = 'partner_translations';
    protected $fillable = ['partner_id','locale','title','alias','description','header','meta_keywords','meta_description'];
}
