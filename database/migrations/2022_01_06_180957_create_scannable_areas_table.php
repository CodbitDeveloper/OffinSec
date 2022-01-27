<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScannableAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::dropIfExists('scannable_areas');

        Schema::create('scannable_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid("location_code");
            $table->string("name");
            $table->unsignedBigInteger("site_id");
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
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
        Schema::dropIfExists('scannable_areas');
    }
}
