<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('sent_by');
            $table->tinyInteger('status')->default(0)->comment('0=pending; 1=sent; 2=failed');
            $table->string('subject');
            $table->text('content');
            $table->string('slug')->nullable();
            $table->tinyInteger('draft')->default(0)->comment('0=not ddraft; 1=draft');
            $table->integer('template_id')->default(1);
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
        Schema::dropIfExists('email_campaigns');
    }
}
