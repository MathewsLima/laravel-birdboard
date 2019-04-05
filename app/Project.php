<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordsActivity;

    protected $fillable = [
        'title', 'description', 'notes', 'user_id',
    ];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function addTask(string $body)
    {
        return $this->tasks()->create(['body' => $body]);
    }
}
