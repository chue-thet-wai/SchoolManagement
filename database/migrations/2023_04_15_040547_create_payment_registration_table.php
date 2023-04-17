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
        Schema::create('payment_registration', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id');
            $table->string('registration_no');
            $table->timestamp('pay_date')->nullable();
            $table->tinyInteger('payment_type')->default(0)->comment('0:monthly,1:yearly');
            $table->timestamp('pay_from_period')->nullable();
            $table->timestamp('pay_to_period')->nullable();
            $table->float('grade_level_fee')->default(0.0);
            $table->float('total_amount')->default(0.0);
            $table->integer('discount_percent')->default(0.0);
            $table->float('net_total')->default(0.0);
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
        Schema::dropIfExists('payment_registration');
    }
};
