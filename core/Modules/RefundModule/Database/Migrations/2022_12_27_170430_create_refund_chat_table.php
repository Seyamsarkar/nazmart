<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_chat', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('via')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('user_agent')->nullable();
            $table->longText('description')->nullable();
            $table->text('subject')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
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
        Schema::dropIfExists('refund_chat');
    }
}
