<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class StandardMolodenskyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_molodensky', function (Blueprint $table) {
            $table->increments('id_std_molodensky');
            $table->unsignedInteger('id_survey');
            $table->double('dlat');
            $table->double("dlon");
            $table->double("dh");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE standard_molodensky ADD FOREIGN KEY (id_survey) REFERENCES surveys(id_survey) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("standard_molodensky");
    }
}
