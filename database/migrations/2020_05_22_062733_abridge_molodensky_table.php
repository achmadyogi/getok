<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AbridgeMolodenskyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abridge_molodensky', function (Blueprint $table) {
            $table->increments('id_abridge_molodensky');
            $table->unsignedInteger('id_survey');
            $table->double('dlat');
            $table->double("dlon");
            $table->double("dh");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE abridge_molodensky ADD FOREIGN KEY (id_survey) REFERENCES surveys(id_survey) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("abridge_molodensky");
    }
}
