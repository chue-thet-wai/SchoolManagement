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
        Schema::create('student_info', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('name');
            $table->string('name_mm');
            $table->timestamp('date_of_birth')->nullable();
            $table->string('gender');
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->string('township');
            $table->string('old_school_name')->nullable();
            $table->string('old_grade')->nullable();
            $table->string('old_academic_year')->nullable();
            $table->string('father_name');
            $table->string('father_name_mm');
            $table->string('mother_name');
            $table->string('mother_name_mm');
            $table->string('father_phone');
            $table->string('mother_phone');
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->string('student_profile')->nullable();
            $table->string('student_biography')->nullable();
            $table->integer('guardian_id')->comment('id from student_guardian table');
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
        Schema::dropIfExists('student_info');
    }
};
