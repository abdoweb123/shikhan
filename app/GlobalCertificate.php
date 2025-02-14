<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class GlobalCertificate extends Model
{

    public $table = "global_certificates";
    protected $fillable = ['title','type','sites_ids','certificate_template_name','is_active'];

    protected $casts = [
        'title' => 'array',
        'sites_ids' => 'array',
        'certificate_template_name' => 'array'
    ];

    public function scopeAdvancedSiteCertificate($query)
    {
       return $query->where('type', 'advanced_site_certificate');
    }

    public function scopeActive($query)
    {
       return $query->where('is_active', 1);
    }




}
