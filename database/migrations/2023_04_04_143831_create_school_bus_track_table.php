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
        Schema::create('school_bus_track', function (Blueprint $table) {
            $table->id();
            $table->string('track_no');
            $table->string('driver_id');
            $table->string('car_type');
            $table->string('car_no');
            $table->string('school_from_time');
            $table->string('school_to_time');
            $table->string('school_from_period');
            $table->string('school_to_period');
            $table->integer('arrive_student_no')->default(0);
            $table->string('township');
            $table->float('two_way_amount');
            $table->float('oneway_pickup_amount');
            $table->float('oneway_back_amount');
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
        Schema::dropIfExists('school_bus_track');
    }
};
