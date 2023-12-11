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
        Schema::table('payment_additional_fee', function (Blueprint $table) {
            $table->renameColumn('payment_id', 'invoice_id')->comment('invoice_id in invoice table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_additional_fee', function (Blueprint $table) {
            //
        });
    }
};
