<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOneKeyInstallersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('one_key_installers', function (Blueprint $table) {
            $table->id();
            $table->integer('proj_id')->default(0);
            $table->integer('apk_id')->default(0);
            $table->boolean('external_flag')->default(false);
            $table->string('label')->nullable();
            $table->string('package_name')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('url')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('one_key_installers');
    }
}
