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
        Schema::create('cancel_registration', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no');
            $table->string('student_id');
            $table->timestamp('cancel_date')->nullable();
            $table->string('refund_amount');
            $table->text('remark');
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
        Schema::dropIfExists('cancel_registration');
    }
};
