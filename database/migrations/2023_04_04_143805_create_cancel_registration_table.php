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
            $table->string('registration_no')->comment('registration id from student_registration table');
            $table->foreign('registration_no')->references('registration_no')->on('student_registration')->onDelete('restrict');
            $table->string('student_id')->comment('student id from student_info table');
            $table->foreign('student_id')->references('student_id')->on('student_info')->onDelete('restrict');
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
