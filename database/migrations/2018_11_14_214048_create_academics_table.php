<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
                $table->primary('id');

                $table->string('id', 18);
                $table->string('first_name', 50);
                $table->string('last_name', 50);
                $table->string('email', 200);
                $table->string('password', 200);
                $table->date('birth_date');
                $table->string('major', 100);

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
