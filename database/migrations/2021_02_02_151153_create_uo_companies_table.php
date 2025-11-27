<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUoCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uo_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->String('code');
            $table->String('name');
            $table->String('nameAR');
            $table->Text('description');
            $table->Text('address');
            $table->Text('latitude');
            $table->Text('longitude');
            $table->Text('city');
           
            $table->String('isActive');
            $table->String('phone');
            $table->String('email');
            $table->String('fax');
            $table->String('image');
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
        Schema::dropIfExists('uo_companies');
    }
}
