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
        Schema::create('student_daily_activity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('class_setup')->onDelete('restrict');
            $table->string('student_id');
            $table->string('registration_id');
            $table->string('activity_id');
            $table->string('activity_name');
            $table->timestamp('date');
            $table->integer('rate');
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
        Schema::dropIfExists('student_daily_activity');
    }
};
