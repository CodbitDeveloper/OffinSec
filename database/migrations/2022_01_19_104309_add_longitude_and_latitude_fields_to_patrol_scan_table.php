<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLongitudeAndLatitudeFieldsToPatrolScanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patrol_scan', function (Blueprint $table) {
            //
            $table->string("longitude")->nullable();
            $table->string("latitude")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patrol_scan', function (Blueprint $table) {
            //
        });
    }
}
