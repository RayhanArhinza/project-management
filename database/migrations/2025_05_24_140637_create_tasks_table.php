<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->string('id')->primary(); // primary key string
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('task_list_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->enum('status', ['open', 'in_progress', 'completed']);
            $table->timestamps();

            // Optional: Add foreign keys if necessary
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            // $table->foreign('task_list_id')->references('id')->on('task_lists')->onDelete('set null');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
