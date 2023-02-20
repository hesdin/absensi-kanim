<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('date_masuk')->nullable();
            $table->enum('status_masuk', ['absen hadir', 'absen terlambat', 'hadir', 'terlambat', 'tidak hadir', 'cuti']);
            $table->string('date_pulang')->nullable();
            $table->enum('status_pulang', ['absen pulang', 'absen lembur', 'pulang', 'lembur'])->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('foto_pulang')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                                        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absens');
    }
}
