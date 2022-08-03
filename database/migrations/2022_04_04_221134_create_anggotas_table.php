<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_anggota');
            $table->string('slug');
            $table->string('nama');
            $table->string('nik');
            $table->string('no_hp');
            $table->string('tdd')->nullable();
            $table->enum('aktif', [
                'aktif',
                'non-aktif',
            ])->default('non-aktif');
            $table->longText('alamat');
            $table->foreignUuid('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggotas');
    }
}
