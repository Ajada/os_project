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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_id')->after('id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->string('client_name')->after('cpf')->nullable();
            $table->string('status')->after('secondary_contact')->default('1');
            $table->string('km')->after('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn([
                'client_name', 
                'km', 
                'status'
            ]);
        });
    }
};
