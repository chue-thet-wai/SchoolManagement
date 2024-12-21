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
        Schema::create('exam_terms_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_terms_id')->constrained('exam_terms')->onDelete('restrict');
            $table->foreignId('subject_id')->constrained('subject')->onDelete('restrict');
            $table->string('subject_image');
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
        Schema::dropIfExists('exam_terms_detail');
    }
};
