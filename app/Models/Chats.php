<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function messages(){
        return $this->belongsTo(Messages::class);
    }
}
