<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = "posts";
    protected $guarded = ["id"];

    function user() {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
