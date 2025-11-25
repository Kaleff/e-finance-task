<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
    ];
}
