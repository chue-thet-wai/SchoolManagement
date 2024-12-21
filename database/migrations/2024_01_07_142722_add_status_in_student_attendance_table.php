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
        Schema::table('student_attendance', function (Blueprint $table) {
            $table->integer('status')->default(0)->comment('0:pending,1:confirm,2:reject')->after('attendance_status');
            $table->string('teacher_remark')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_attendance', function (Blueprint $table) {
            //
        });
    }
};
