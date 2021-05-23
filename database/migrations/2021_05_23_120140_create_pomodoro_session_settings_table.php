<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePomodoroSessionSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('pomodoro_session_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->time('pomodoro_duration');
            $table->time('small_break_duration');
            $table->time('big_break_duration');
            $table->integer('pomodoro_quantity');
            $table->timestamps();

            $table->foreignId('user_setting_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pomodoro_session_settings');
    }
}
