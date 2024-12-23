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
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->nullable()->default(null)->constrained('grade')->onDelete('restrict');
            $table->foreignId('academic_year_id')->constrained('academic_year')->onDelete('restrict');
            $table->string('title');
            $table->string('description');
            $table->timestamp('event_from_date')->nullable();
            $table->timestamp('event_to_date')->nullable();
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
        Schema::dropIfExists('event');
    }
};
