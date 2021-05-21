<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePomodoroSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('pomodoro_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('pomodoro_duration');
            $table->time('small_pause_duration');
            $table->time('big_pause_duration');
            $table->integer('pomodoro_quantity');
            $table->timestamps();

            $table->foreignId('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pomodoro_sessions');
    }
}
