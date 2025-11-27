<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('employee_id');
            $table->string('assign_date')->nullable();
            $table->string('task_date')->nullable();
            $table->string('arrival_time')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('drop_time')->nullable();
            $table->string('booking_number')->nullable();
            $table->string('task_status')->nullable();
            $table->string('task_address')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
