<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('tenant_id');
            $table->string('company_name');
            $table->string('address');
            $table->string('email');
            $table->string('company_phone');
            $table->string('website')->default('www.cnxretail.com');
            $table->string('contact_full_name');
            $table->string('contact_position');
            $table->string('contact_email')->nullable();
            $table->string('contact_mobile')->nullable();
            $table->string('communication_channel')->nullable();
            $table->string('whatsapp_contact')->nullable();
            $table->string('hear_about_us')->nullable();
            $table->time('preferred_time')->nullable();
            $table->string('slug');
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
        Schema::dropIfExists('contacts');
    }
}
