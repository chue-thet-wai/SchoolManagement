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
        Schema::create('ferry_student', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no');
            $table->string('student_id');
            $table->string('phone');
            $table->text('address');
            $table->string('township');
            $table->integer('status')->default(0)->comment('0:Pending, 1:Confirm, 2:Active , 3:Inactive , 4:Reject');
            $table->integer('way')->default(2)->comment('1:One Way PickUp,2:One Way Back, 3:Two Way');
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
        Schema::dropIfExists('ferry_student');
    }
};
