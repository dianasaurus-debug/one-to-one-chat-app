<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    use HasFactory;
    protected $fillable = [
        'user1_id',
        'user2_id',
    ];
    public function chats(){
        return $this->hasManyThrough(Chats::class, Messages::class);
    }
    public function messages(){
        return $this->hasMany(Messages::class);
    }
    public function deleteChats(){
        $this->chats()->where('user_id', auth()->id())->delete();
    }
    public function deleteMessages(){
        $this->messages()->delete();
    }
    public function block(){
        $this->blocked = true;
        $this->blocked_by = auth()->id();
        $this->save();
    }
    public function unblock(){
        $this->blocked = false;
        $this->blocked_by = null;
        $this->save();
    }
}
