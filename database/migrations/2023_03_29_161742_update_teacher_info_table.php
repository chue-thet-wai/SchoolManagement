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
        Schema::table('teacher_info', function($table) {
            $table->timestamp('login_date')->nullable()->after('profile_image');
            $table->timestamp('logout_date')->nullable()->after('login_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_info', function($table) {
            $table->dropColumn('login_date');
            $table->dropColumn('logout_date');
        });
    }
};