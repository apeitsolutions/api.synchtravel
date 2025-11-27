<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name','15');
            $table->string('last_name','15');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('address')->nullable();
            $table->string('address_latitude')->nullable();
            $table->string('address_longitude')->nullable();
            $table->string('salary');
            $table->string('birth_date');
            $table->string('contact_info');
            $table->string('gender');
            $table->string('position');
            $table->string('schedule');
            $table->string('employee_status')->nullable();
            $table->string('image');
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
        Schema::dropIfExists('employees');
    }
}
