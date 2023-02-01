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
        Schema::table('responsible_services', function (Blueprint $table) {
            $table->unsignedBigInteger('responsible_id')->after('service_id');
            $table->foreign('responsible_id')->references('id')->on('responsibles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responsible_services', function (Blueprint $table) {
            $table->dropForeign(['responsible_id']);
            $table->dropColumn('responsible_id');
        });
    }
};
