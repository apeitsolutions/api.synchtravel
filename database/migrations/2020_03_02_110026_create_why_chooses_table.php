<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhyChoosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('why_chooses', function (Blueprint $table) {
            $table->increments('id');
             $table->String('title');
            $table->String('arabic_title');
            $table->String('image');
            $table->String('content');
            $table->String('arabic_content');
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
        Schema::dropIfExists('why_chooses');
    }
}
