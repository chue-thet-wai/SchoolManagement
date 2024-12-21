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
            $table->foreignId('academic_year_id')->constrained('academic_year')->onDelete('restrict');
            $table->foreignId('grade_id')->constrained('grade')->onDelete('restrict');
            $table->foreignId('branch_id')->constrained('branch')->onDelete('restrict');
            $table->float('grade_level_amount')->default(0.0);
            $table->integer('fee_type')->default(0)->comment('0:Monthly,1:OneTime');
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
