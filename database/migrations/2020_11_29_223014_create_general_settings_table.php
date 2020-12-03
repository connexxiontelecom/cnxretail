<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('business_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('opening_hour')->nullable();
            $table->string('closing_hour')->nullable();
            $table->string('logo')->nullable();
            $table->string('site_icon')->nullable();
            $table->text('privacy')->nullable();
            $table->string('website')->nullable();
            $table->integer('industry')->nullable();
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
        Schema::dropIfExists('general_settings');
    }
}
