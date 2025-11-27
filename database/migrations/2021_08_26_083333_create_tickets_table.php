<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('umrah_operator');
            $table->string('email');
            $table->string('phone');
            $table->string('ticket_type');
            $table->string('ticket_priorty');
            $table->text('subject');
            $table->text('description');
             $table->string('additional_email')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
