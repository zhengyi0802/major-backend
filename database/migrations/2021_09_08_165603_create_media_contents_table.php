<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('catagory_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('preview');
            $table->string('mime_type');
            $table->string('url');
            $table->string('url_http')->nullable();
            $table->boolean('status');
            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('media_contents');
    }
}
