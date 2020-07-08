<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class LaufTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lauf', function (Blueprint $table) {
            $table->increments('id_lauf');
            $table->unsignedInteger('id_survey');
            $table->double('a');
            $table->double('b');
            $table->double("c");
            $table->double("d");
            $table->double('dx');
            $table->double('dy');
            $table->double("x_centroid");
            $table->double("y_centroid");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE lauf ADD FOREIGN KEY (id_survey) REFERENCES surveys(id_survey) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("lauf");
    }
}
