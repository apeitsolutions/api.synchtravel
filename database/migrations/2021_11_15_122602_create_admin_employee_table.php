<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_employee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name',15); 
            $table->string('last_name',15);
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
            $table->string('admin_id')->nullable();
            $table->string('image');
            $table->timestamps();
            $table->string('req_status')->nullable();
            $table->string('is_active')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_employee');
    }
}
