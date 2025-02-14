<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberGlobalCertificate extends Model
{
    public $table = "member_global_certificate";

    protected $fillable = [
        'global_certificate_id','user_id','code','is_active','print_count'
    ];

    public function member()
    {
        return $this->belongsTo('App\member','user_id');
    }

    public function global_certificate()
    {
        return $this->belongsTo('App\GlobalCertificate','global_certificate_id');
    }

    public function scopeActive($query)
    {
       return $query->where('is_active', 1);
    }

}
