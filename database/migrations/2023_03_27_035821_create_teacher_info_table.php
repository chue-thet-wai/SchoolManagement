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
        Schema::create('teacher_info', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->comment('user id from users table');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('restrict');
            //$table->integer('grade_id')->default(0)->comment('id for grade table and 0 is no grade');
            $table->string('name');
            $table->string('name_mm');
            $table->string('login_name');
            $table->timestamp('startworking_date')->nullable();
            $table->string('gender');
            $table->string('email');
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->text('remark')->nullable();
            $table->tinyInteger('resign_status')->default(1)->comment('0 :resign , 1:active');
            $table->timestamp('resign_date')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('position')->nullable();
            $table->string('qualification_name')->nullable();
            $table->string('year_attended')->nullable();
            $table->string('qualification_desc')->nullable();
            $table->string('job_title')->nullable();
            $table->string('company_name')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('university')->nullable();
            $table->string('education_year')->nullable();
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
        Schema::dropIfExists('teacher_info');
    }
};
