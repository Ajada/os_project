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
            $table->dropColumn('client_name');
            $table->dropColumn('cpf');
            $table->string('main_contact')->after('user_id');
            $table->string('secondary_contact')->default('not informed')->after('main_contact')->nullable();
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
            $table->dropColumn(['main_contact', 'secondary_contact']);
            $table->string('client_name')->after('user_id');
            $table->string('cpf')->after('client_name');
        });
    }
};
