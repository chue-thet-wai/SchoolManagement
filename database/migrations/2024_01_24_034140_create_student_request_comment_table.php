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
        Schema::create('student_request_comment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_request_id')->constrained('student_request')->onDelete('restrict');
            $table->string('comment_by_parent')->nullable();
            $table->string('comment_by_school')->nullable();
            $table->text('comment');
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
        Schema::dropIfExists('student_request_comment');
    }
};
