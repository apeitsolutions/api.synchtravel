<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferVehicleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_vehicle_types', function (Blueprint $table) {
               $table->increments('id');
            $table->String('code');
            $table->String('name');
            $table->String('nameAR');
            $table->Text('description');
            $table->String('maxCapacity');
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
        Schema::dropIfExists('transfer_vehicle_types');
    }
}
