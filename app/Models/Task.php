<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Project;

class Task extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'project_id', 'task_list_id', 'title', 'description', 'user_id', 'due_date', 'priority', 'status'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Saat membuat task baru, generate custom ID
        static::creating(function ($task) {
            $project = Project::findOrFail($task->project_id);
            $abbr = strtoupper(Str::limit(str_replace(' ', '', $project->name), 3, ''));
            $date = now()->format('Ymd');
            $taskId = $abbr . '-' . $date;
            $latest = self::where('id', 'LIKE', "$taskId%")->count();
            $task->id = $taskId . str_pad($latest + 1, 3, '0', STR_PAD_LEFT);
        });

        // Setelah task berhasil dibuat, buat notifikasi baru untuk user yang bersangkutan
        static::created(function ($task) {
            \App\Models\Notification::create([
                'user_id'    => $task->user_id, // asumsikan field ini menunjuk ke user yang menerima task
                'title'      => $task->title,
                'project_id' => $task->project_id,
                'description'=> $task->description,
                'due_date'   => $task->due_date,
            ]);
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function taskList()
    {
        return $this->belongsTo(TaskList::class, 'task_list_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
