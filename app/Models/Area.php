<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    /** @use HasFactory<\Database\Factories\AreaFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'name',
    ];


    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function taskArea(){
        return $this->hasMany(TaskArea::class);
    }


    public function responses(){
        return $this->hasMany(Response::class);
    }

}
