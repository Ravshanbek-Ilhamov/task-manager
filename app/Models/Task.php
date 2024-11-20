<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'performer',
        'title',
        'file',
        'period',        
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function taskAreas()
    {
        return $this->hasMany(TaskArea::class);
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'task_areas', 'task_id', 'area_id');
    }

    public function responses(){
        return $this->hasMany(Response::class);
    }


}
