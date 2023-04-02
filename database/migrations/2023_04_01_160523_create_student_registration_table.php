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
        Schema::create('student_registration', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->comment('student id from student_info table');
            $table->string('old_class_id')->nullable()->comment('id from class_setup table');
            $table->string('new_class_id')->nullable()->comment('id from class_setup table');
            $table->timestamp('registration_date')->nullable();
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
        Schema::dropIfExists('student_registration');
    }
};
