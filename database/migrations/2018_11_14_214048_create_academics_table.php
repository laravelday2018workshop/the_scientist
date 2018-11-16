<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics',
            function (Blueprint $table) {
                $table->increments('id');

                $table->string('registration_number', 18);
                $table->string('first_name', 50);
                $table->string('last_name', 50);
                $table->date('birth_date');
                $table->string('major', 100);
                $table->dateTime('registered_at');

                $table->index('registration_number');
                $table->index(['first_name', 'last_name']);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academics');
    }
}
