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
        Schema::create('payment_additional_fee', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->comment('invoice_id in invoice table');
            $table->string('additional_fee_id')->comment('id for additional_fee table');
            $table->float('additional_amount')->default(0.0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('payment_additional_fee');
    }
};
