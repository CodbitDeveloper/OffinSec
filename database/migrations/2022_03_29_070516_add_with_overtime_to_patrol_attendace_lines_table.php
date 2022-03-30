<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWithOvertimeToPatrolAttendaceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patrol_attendace_lines', function (Blueprint $table) {
            //
            $table->boolean("with_overtime");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patrol_attendace_lines', function (Blueprint $table) {
            //
        });
    }
}
