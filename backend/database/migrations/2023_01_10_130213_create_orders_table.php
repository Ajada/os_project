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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('mileage')->nullable();
            $table->string('client_name')->nullable();
            $table->string('cpf')->nullable();
            $table->string('name_main_contact')->nullable();
            $table->string('number_main_contact')->nullable();
            $table->string('name_secondary_contact')->nullable();
            $table->string('number_secondary_contact')->nullable();
            $table->longText('problem_related');
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
        Schema::dropIfExists('orders');
    }
};
