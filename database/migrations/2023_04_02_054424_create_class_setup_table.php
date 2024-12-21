<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->foreignId('branch_id')->constrained('branch')->onDelete('restrict');
            $table->foreignId('room_id')->constrained('room')->onDelete('restrict');
            $table->foreignId('grade_id')->constrained('grade')->onDelete('restrict');
            $table->foreignId('section_id')->constrained('section')->onDelete('restrict');
            $table->foreignId('academic_year_id')->constrained('academic_year')->onDelete('restrict');
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
