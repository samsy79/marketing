<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Behavior extends Model
{
    protected $fillable = ['user_id', 'page_visited'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
