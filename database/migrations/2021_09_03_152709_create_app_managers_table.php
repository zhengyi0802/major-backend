<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_managers', function (Blueprint $table) {
            $table->id();
            $table->integer('proj_id')->default(0);
            $table->integer('apk_id')->default(0);
            $table->boolean('market_id')->default(false);
            $table->boolean('installer_flag')->default(false);
            $table->integer('delaytime')->default(5);
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
        Schema::dropIfExists('app_managers');
    }
}
