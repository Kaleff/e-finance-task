<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'status',
        'assigned_to',
        'due_date',
    ];
}
