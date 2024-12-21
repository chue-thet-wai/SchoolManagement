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
        Schema::create('teacher_class', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_id')->comment('user id from teacher_info table');
            $table->foreign('teacher_id')->references('user_id')->on('teacher_info')->onDelete('restrict');
            $table->foreignId('class_id')->constrained('class_setup')->onDelete('restrict');
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
        Schema::dropIfExists('teacher_class');
    }
};
