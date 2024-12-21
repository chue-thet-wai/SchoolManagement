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
        Schema::create('guardian_device', function (Blueprint $table) {
            $table->id();
            $table->string('guardian_id')->comment('id of student_guardian table');
            $table->string('device_id')->nullable();
            $table->longText('device_token');
            $table->string('device_os_type');
            $table->timestamp('last_activated_at');
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
        Schema::dropIfExists('guardian_device');
    }
};
