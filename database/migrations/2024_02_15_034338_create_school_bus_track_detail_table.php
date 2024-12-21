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
        Schema::create('school_bus_track_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_bus_track_id')->constrained('school_bus_track')->onDelete('restrict');
            $table->string('ferry_student_id');
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
        Schema::dropIfExists('school_bus_track_detail');
    }
};
