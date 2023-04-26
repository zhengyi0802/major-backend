<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQACatagoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('q_a_catagories', function (Blueprint $table) {
            $table->id();
            $table->integer('position')->default();
            $table->string('name');
            $table->string('descriptions')->nullable();
            $table->boolean('status');
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('q_a_catagories');
    }
}
