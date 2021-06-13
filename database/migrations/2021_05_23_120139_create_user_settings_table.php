<?php

use App\Enums\FrontendDisplayFormat;
use App\Enums\FrontendTheme;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('theme')->default(FrontendTheme::LIGHT);
            $table->string('display_format')->default(FrontendDisplayFormat::TWENTY_FOUR_HOURS);
            $table->timestamps();

            $table->foreignUuid('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
}
