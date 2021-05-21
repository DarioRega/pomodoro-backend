<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('pomodoro_session_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('pomodoro_duration');
            $table->integer('small_pause_duration');
            $table->integer('big_pause_duration');
            $table->integer('pomodoro_quantity');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pomodoro_session_settings');
    }
}
