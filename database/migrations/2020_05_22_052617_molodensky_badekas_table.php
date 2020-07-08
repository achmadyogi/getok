<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MolodenskyBadekasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('molodensky_badekas', function (Blueprint $table) {
            $table->increments('id_molobas');
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
            $table->double("x_centroid");
            $table->double("y_centroid");
            $table->double("z_centroid");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE molodensky_badekas ADD FOREIGN KEY (id_survey) REFERENCES surveys(id_survey) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("molodensky_badekas");
    }
}
