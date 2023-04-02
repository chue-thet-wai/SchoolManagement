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
        Schema::create('class_setup', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('room_id')->comment('id from room table');
            $table->integer('grade_id')->comment('id for grade table');
            $table->integer('section_id')->comment('id for section table');
            $table->integer('academic_year_id')->comment('id from academic_year table');
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
        Schema::dropIfExists('class_setup');
    }
};
