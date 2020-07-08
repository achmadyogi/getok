<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EllipsoidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ellipsoids', function (Blueprint $table) {
            $table->increments('id_ellipsoid');
            $table->integer('year');
            $table->string('ellipsoid_name');
            $table->double('a');
            $table->double('b');
            $table->double('f')->comments("1/f");
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
        Schema::dropIfExists('datum');
    }
}
