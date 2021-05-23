<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsTable extends Migration
{
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->time('duration');
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('skipped_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->time('resting_time');
            $table->timestamps();

            $table->foreignId('pomodoro_session_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('steps');
    }
}