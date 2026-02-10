<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class NewTaskNotification extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Tentukan channel notifikasi.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Format data notifikasi untuk disimpan ke database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title'        => $this->task->title,
            'project_name' => $this->task->project->name,
            'description'  => $this->task->description,
            'due_date'     => $this->task->due_date->toDateTimeString(),
        ];
    }
}
