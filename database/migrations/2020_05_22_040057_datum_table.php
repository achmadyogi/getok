<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DatumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datums', function (Blueprint $table) {
            $table->increments('id_datum');
            $table->string('datum_name');
            $table->unsignedInteger('id_ellipsoid');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE datums ADD FOREIGN KEY (id_ellipsoid) REFERENCES ellipsoids(id_ellipsoid) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("datums");
    }
}
