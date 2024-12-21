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
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('class_setup')->onDelete('restrict');
            $table->foreignId('exam_terms_id')->constrained('exam_terms')->onDelete('restrict');
            $table->string('student_id')->nullable()->comment('student id from student_info table');
            $table->foreign('student_id')->references('student_id')->on('student_info')->onDelete('restrict');
            $table->foreignId('subject_id')->constrained('subject')->onDelete('restrict');
            $table->integer('mark')->default(0);
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
        Schema::dropIfExists('exam_marks');
    }
};
