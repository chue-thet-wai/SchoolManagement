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
        Schema::create('message', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('class_id')->nullable()->constrained('class_setup')->onDelete('restrict');
            $table->string('student_id')->nullable()->comment('student id from student_info table');
            $table->foreign('student_id')->references('student_id')->on('student_info')->onDelete('restrict');
            $table->text('description');
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('message');
    }
};
