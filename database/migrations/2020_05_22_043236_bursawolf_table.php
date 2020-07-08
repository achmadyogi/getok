<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BursawolfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bursawolf', function (Blueprint $table) {
            $table->increments('id_bursawolf');
            $table->unsignedInteger('id_survey');
            $table->double('dx');
            $table->double("dx_uncertainty");
            $table->double("dy");
            $table->double("dy_uncertainty");
            $table->double("dz");
            $table->double("dz_uncertainty");
            $table->double('ex');
            $table->double("ex_uncertainty");
            $table->double("ey");
            $table->double("ey_uncertainty");
            $table->double("ez");
            $table->double("ez_uncertainty");
            $table->double("ds");
            $table->double("ds_uncertainty");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE bursawolf ADD FOREIGN KEY (id_survey) REFERENCES surveys(id_survey) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("bursawolf");
    }
}
