<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class MemberPost extends Model
{
    protected $table = 'member_posts';
    protected $fillable = ['member_id','post_id','course_id'];

}
