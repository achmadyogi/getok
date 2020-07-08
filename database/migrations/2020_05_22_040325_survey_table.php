<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id_survey');
            $table->string('survey_name');
            $table->date('survey_date');
            $table->unsignedInteger("id_old_datum");
            $table->unsignedInteger("id_new_datum");
            $table->unsignedInteger("id_user");
            $table->text("description");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE surveys ADD FOREIGN KEY (id_old_datum) REFERENCES datums(id_datum) ON DELETE CASCADE");
        DB::statement("ALTER TABLE surveys ADD FOREIGN KEY (id_new_datum) REFERENCES datums(id_datum) ON DELETE CASCADE");
        DB::statement("ALTER TABLE surveys ADD FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("surveys");
    }
}
