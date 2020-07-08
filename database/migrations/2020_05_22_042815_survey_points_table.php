<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SurveyPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_points', function (Blueprint $table) {
            $table->increments('id_point');
            $table->unsignedInteger('id_survey');
            $table->double('lat_old');
            $table->double("lon_old");
            $table->double("h_old");
            $table->double("lat_new");
            $table->double("lon_new");
            $table->double("h_new");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE survey_points ADD FOREIGN KEY (id_survey) REFERENCES surveys(id_survey) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("survey_points");
    }
}
