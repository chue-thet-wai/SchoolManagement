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
        Schema::create('guardian_pocket_money', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('guardian_id');
            $table->string('card_id');
            $table->string('amount');
            $table->string('status')->comment('0:Pending, 1:Confirm, 2:Reject');
            $table->string('remrk')->nullable();
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
        Schema::dropIfExists('guardian_pocket_money');
    }
};
