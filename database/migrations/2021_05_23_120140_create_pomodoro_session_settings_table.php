<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePomodoroSessionSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('pomodoro_session_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->integer('pomodoro_duration');
            $table->integer('small_break_duration');
            $table->integer('big_break_duration');
            $table->integer('pomodoro_quantity');
            $table->timestamps();

            $table->foreignUuid('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pomodoro_session_settings');
    }
}
