<?php

use App\Models\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        TaskStatus::create([
            'name' => 'TODO'
        ]);
        TaskStatus::create([
            'name' => 'IN_PROGRESS'
        ]);
        TaskStatus::create([
            'name' => 'DONE'
        ]);
        TaskStatus::create([
            'name' => 'ARCHIVED'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('task_statuses');
    }
}
