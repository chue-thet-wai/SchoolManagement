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
        Schema::create('staff_info', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->integer('department_id')->default(0);
            $table->string('name');
            $table->string('login_name');
            $table->timestamp('startworking_date')->nullable();
            $table->string('email');
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->text('remark')->nullable();
            $table->tinyInteger('resign_status')->default(1)->comment('0 :resign , 1:active');
            $table->timestamp('resign_date')->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamp('login_date')->nullable();
            $table->timestamp('logout_date')->nullable();
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
        Schema::dropIfExists('staff_info');
    }
};
