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
            $table->string('client_name');
            $table->string('cpf');
            $table->string('plate');
            $table->string('car_model')->nullable();
            $table->string('stats')->default('em avaliação'); 
            //em avaliação //aguardando peça //aguardando mecânico //em execução //a faturar //pago //aguardando entrega //finalizado
            $table->longText('problem_related');
            $table->longText('problem_found');
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
