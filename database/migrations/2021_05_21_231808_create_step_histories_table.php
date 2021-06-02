<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('step_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('action');
            $table->timestamps();

            $table->foreignUuid('step_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('step_histories');
    }
}
