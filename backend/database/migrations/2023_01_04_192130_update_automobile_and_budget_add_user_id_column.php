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
        Schema::table('automobiles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id')->index()->nullable();
            $table->foreign('user_id')->references('user_id')->on('orders');
        });

        Schema::table('budget', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id')->index()->nullable();
            $table->foreign('user_id')->references('user_id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('automobiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('budget', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};
