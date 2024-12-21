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
        Schema::create('book_register', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('book_category')->onDelete('restrict');
            $table->string('title');
            $table->string('author');
            $table->string('description')->nullable();
            $table->integer('quantity')->default(0);
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
        Schema::dropIfExists('book_register');
    }
};
