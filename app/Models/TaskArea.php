<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskArea extends Model
{
    /** @use HasFactory<\Database\Factories\TaskAreaFactory> */
    use HasFactory;

    protected $fillable  = [
        'category_id',
        'period',
        'area_id',
        'task_id',
        'status',
    ];

    public function areas(){
        return $this->belongsTo(Area::class,'area_id');
    }

    public function tasks(){
        return $this->belongsTo(Task::class,'task_id');
    }

//     public function areas()
// {
//     return $this->belongsTo(Area::class, 'area_id');
// }

}
