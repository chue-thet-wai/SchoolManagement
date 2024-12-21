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
        Schema::create('book_rent', function (Blueprint $table) {
            $table->id();
            $table->string('book_title');
            $table->string('student_id')->comment('student id from student_info table');
            $table->foreign('student_id')->references('student_id')->on('student_info')->onDelete('restrict');
            $table->timestamp('rent_date')->nullable();
            $table->timestamp('return_date')->nullable();
            $table->timestamp('actual_return_date')->nullable();
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
        Schema::dropIfExists('book_rent');
    }
};
