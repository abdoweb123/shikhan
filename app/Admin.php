<?php
namespace App;
use App\Notifications\AdminResetPasswordNotification;
// use Spatie\Permission\Traits\HasRoles;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
// class Admin extends User
{
    // use HasRoles;
    protected $table = 'admins';
    protected $guard_name = 'admin';

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public function getImagePathAttribute()
    {
        return (substr($this->image, 0, 4) === 'http') ? $this->image : (\Storage::exists($this->image ?? '') ? url(\Storage::url($this->image)) : asset('assets/img/default/admin.png') );
    }

    public static function default_image()
    {
        $name = \Str::random(20);
        Storage::disk('storage')->copy('framework/backup/default/admins.png', 'app/public/admins/admin-'.$name.'.png');
        return 'admins/admin-'.$name.'.png';
    }
}
