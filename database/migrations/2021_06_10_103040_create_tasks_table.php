<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamp('deadline')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreignUuid('task_status_id');
            $table->foreignUuid('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
