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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('platform_name');
            $table->string("access_token");
            $table->integer("expires_in");
            $table->string("refresh_token");
            $table->integer("refresh_expires_in");
            $table->string("account_email");
            $table->boolean("is_sync_enabled");// TODO: also disable in transaction listenter
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
        Schema::dropIfExists('integrations');
    }
};
