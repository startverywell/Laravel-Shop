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
        Schema::table('surveys', function (Blueprint $table) {
            $table->smallInteger('brand_description_show')->nullable()->default(0);
            $table->smallInteger('title_show')->nullable()->default(0);
            $table->string('widget_image')->nullable();
            $table->text('widget_text')->nullable();
            $table->smallInteger('widget_show')->nullable()->default(0);
            $table->integer('widget_height')->nullable()->default(0);
            $table->string('widget_height_unit');
            $table->integer('widget_width')->nullable()->default(0);
            $table->string('widget_width_unit');
            $table->string('widget_position');
            $table->string('widget_text_color');
            $table->string('widget_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surveys', function (Blueprint $table) {
            //
        });
    }
};
