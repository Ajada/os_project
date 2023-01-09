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
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('responsible');
            $table->dropColumn('external_parts');
            $table->dropColumn('service_description');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->integer('order_id')->after('id');
            $table->foreign('order_id')->references('id')->on('id');
            $table->longText('description');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign('order_id');
            $table->dropColumn('order_id');
            $table->dropColumn('description');
            $table->dropColumn('status');
        });
    }
};
