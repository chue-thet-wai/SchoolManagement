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
        Schema::create('grade_level_fee', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_year_id')->comment('id from academic_year table');
            $table->integer('grade_id')->comment('id for grade table');
            $table->integer('branch_id')->comment('id for branch table');
            $table->float('grade_level_amount')->default(0.0);
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
        Schema::dropIfExists('grade_level_fee');
    }
};
