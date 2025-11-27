<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_users', function (Blueprint $table) {
            $table->id()->increment();
            $table->string('name');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('gender');
            $table->string('country');
            $table->string('phone');
            $table->string('passport');
            $table->string('nationlaity');
            $table->string('address');
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
        Schema::dropIfExists('client_users');
    }
}
