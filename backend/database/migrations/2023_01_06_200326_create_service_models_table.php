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
        Schema::create('services', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->uuid('user_id')->index()->nullable();
            $table->foreign('id')->references('id')->on('orders');
            $table->string('responsible')->nullable();
            $table->boolean('external_parts')->nullable();
            $table->longText('service_description'); // json com todos os dados\itens selecionados pelo mecanico
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
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['id']);
        });

        Schema::dropIfExists('services');
    }
};
