<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatrolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('patrols');
        
        Schema::create('patrols', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("patrol_officer");
            $table->text("notes")->nullable();
            $table->unsignedBigInteger("site_id");
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
        Schema::dropIfExists('patrols');
    }
}
