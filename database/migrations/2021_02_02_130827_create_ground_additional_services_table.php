<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroundAdditionalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ground_additional_services', function (Blueprint $table) {
             $table->increments('id');
            $table->String('code');
            $table->String('name');
            $table->String('nameAR');
            $table->Text('description');
            $table->Text('descriptionAR');
            $table->String('isActive');
            $table->String('provider');
            $table->String('vendor');
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
        Schema::dropIfExists('ground_additional_services');
    }
}
