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
        Schema::table('history_vehicle_io', function (Blueprint $table) {
            $table->string('date')->after('vehicle_id');
            $table->string('type')->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_vehicle_io', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('type');
        });
    }
};
