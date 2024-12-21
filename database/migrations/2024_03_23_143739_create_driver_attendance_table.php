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
        Schema::create('driver_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('driver_id');
            $table->date('attendance_date');
            $table->string('status')->default('present');
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('driver_attendance');
    }
};
