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
        Schema::create('book_rent', function (Blueprint $table) {
            $table->id();
            $table->integer('book_id')->comment('id in book_register table');
            $table->integer('student_id')->comment('id for student_info table');
            $table->timestamp('rent_date')->nullable();
            $table->timestamp('return_date')->nullable();
            $table->timestamp('actual_return_date')->nullable();
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
        Schema::dropIfExists('book_rent');
    }
};
