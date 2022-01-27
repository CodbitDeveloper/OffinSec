<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatrolScanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('patrol_scan');
        
        Schema::create('patrol_scan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("patrol_id");
            $table->unsignedBigInteger("scannable_area_id");
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
        Schema::dropIfExists('patrol_scan');
    }
}
