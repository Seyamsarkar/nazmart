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
        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedInteger('renew_status')->nullable();
            $table->unsignedInteger('is_renew')->default(0);
            $table->string('start_date')->nullable();
            $table->string('expire_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('renew_status');
            $table->dropColumn('is_renew');
            $table->dropColumn('start_date');
            $table->dropColumn('expire_date');
        });
    }
};
