<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatrolAttendanceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patrol_attendance_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patrol_attendance_id');
            $table->string("guard_id");
            $table->boolean("present");
            $table->boolean("applicable");
            $table->string("reliever_id")->nullable();
            $table->boolean("with_permission");
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
        Schema::dropIfExists('patrol_attendance_lines');
    }
}
