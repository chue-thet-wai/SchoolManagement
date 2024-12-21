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
        Schema::create('student_request', function (Blueprint $table) {
            $table->id();
            $table->string('request_type');
            $table->foreignId('class_id')->constrained('class_setup')->onDelete('restrict');
            $table->string('student_id')->comment('student id from student_info table');
            $table->foreign('student_id')->references('student_id')->on('student_info')->onDelete('restrict');
            $table->string('registration_id')->comment('registration no from student_registration table');
            $table->foreign('registration_id')->references('registration_no')->on('student_registration')->onDelete('restrict');
            $table->string('request_by_parent')->nullable();
            $table->string('request_by_school')->nullable();
            $table->text('message')->nullable();
            $table->string('photo')->nullable();
            $table->timestamp('date')->nullbale();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamp('guardian_last_read_at')->nullable();
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
        Schema::dropIfExists('student_request');
    }
};
