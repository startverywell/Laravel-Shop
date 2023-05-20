<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->smallInteger('status')->nullable();
            $table->string('background_color', 20)->nullable();
            $table->string('char_color', 20)->nullable();
            $table->string('border_color', 20)->nullable();
            $table->string('callout_color', 20)->nullable();
            $table->integer('gradient_color')->nullable();
            $table->string('token', 20)->nullable();
            $table->smallInteger('progress_status')->nullable()->default(0);
            $table->smallInteger('qrcode_show')->nullable()->default(0);
            $table->string('profile_path')->nullable();
            $table->string('brand_description')->nullable();
            $table->string('brand_name')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveys');
    }
};
