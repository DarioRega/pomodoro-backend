<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePomodoroSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('pomodoro_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('goals')->nullable();
            $table->time('pomodoro_duration');
            $table->time('small_break_duration');
            $table->time('big_break_duration');
            $table->timestamp('end_time')->nullable();
            $table->integer('pomodoro_quantity');
            $table->timestamps();

            $table->foreignUuid('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pomodoro_sessions');
    }
}
