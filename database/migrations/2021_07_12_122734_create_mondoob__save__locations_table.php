<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMondoobSaveLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mondoob__save__locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('employee_id');
            $table->string('employee_email');
            $table->string('loc_address')->nullable();
            $table->string('loc_latitude')->nullable();
            $table->string('loc_longitude')->nullable();
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
        Schema::dropIfExists('mondoob__save__locations');
    }
}
