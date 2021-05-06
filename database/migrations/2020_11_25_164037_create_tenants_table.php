<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('industry_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('date_format_id');
            $table->unsignedBigInteger('currency_id');
            $table->string('site_address')->nullable();
            $table->string('website')->nullable();
            $table->string('company_name');
            $table->string('email');
            $table->string('use_case');
            $table->string('role')->nullable();
            $table->string('phone')->nullable();
            $table->double('team_size')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->tinyInteger('account_status')->default(1); //0=expired; 1=active
            $table->text('company_policy')->nullable();
            $table->string('downloadable_policy')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->string('downloadable_privacy')->nullable();
            $table->string('email_signature')->nullable();
            $table->string('email_signature_image')->nullable();
            $table->string('address')->nullable();
            $table->string('active_sub_key')->nullable();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('nature_of_business')->nullable();
            $table->text('slug')->nullable();
            $table->text('invoice_terms')->nullable();
            $table->text('receipt_terms')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('public_key')->nullable();
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
        Schema::dropIfExists('tenants');
    }
}
