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
        Schema::table('student_guardian', function (Blueprint $table) {
            $table->string('secondary_phone')->nullable()->after('phone');
            $table->string('photo')->nullable()->after('secondary_phone');
            $table->string('nrc')->nullable()->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_guardian', function (Blueprint $table) {
            $table->dropColumn('secondary_phone');
            $table->dropColumn('photo');
            $table->dropColumn('nrc');
        });
    }
};
