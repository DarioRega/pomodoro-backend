<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepActionsTable extends Migration
{
    public function up()
    {
        Schema::create('step_actions', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->timestamps();

            $table->foreignId('step_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('step_action_records');
    }
}
