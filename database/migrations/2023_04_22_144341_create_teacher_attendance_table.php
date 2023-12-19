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
        Schema::create('teacher_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->timestamp('attendance_date');
            $table->tinyInteger('attendance_status')->default(1)->comment('2: leave , 1 :present , 0:absent');
            $table->text('remark')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('teacher_attendance');
    }
};
