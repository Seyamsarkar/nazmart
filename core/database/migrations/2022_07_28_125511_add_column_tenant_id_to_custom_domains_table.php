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
        // tenant_id = old_domain
        Schema::table('custom_domains', function (Blueprint $table) {
            $table->string('old_domain')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_domains', function (Blueprint $table) {
            $table->dropColumn('old_domain');
        });
    }
};
