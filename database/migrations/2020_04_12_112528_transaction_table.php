<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id_transaction');
            $table->unsignedInteger('id_user')->nullable();
            $table->unsignedInteger('id_app');
            $table->boolean('is_active');
            $table->boolean('is_finished');
            $table->string('file')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE transactions ADD FOREIGN KEY (id_app) REFERENCES apps(id_app) ON DELETE CASCADE");
        DB::statement("ALTER TABLE transactions ADD FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
