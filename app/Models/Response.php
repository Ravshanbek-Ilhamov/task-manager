<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    /** @use HasFactory<\Database\Factories\ResponseFactory> */
    use HasFactory;

    protected $fillable = [
        'task_id',
        'area_id',
        'title',
        'file',
        'status',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    
}
