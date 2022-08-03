<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_pembayaran');
            $table->bigInteger('total_bayar')->default(0);
            $table->integer('bulan')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->enum('aktif', [
                'aktif',
                'non-aktif',
            ])->default('non-aktif');
            $table->foreignUuid('anggota_id')->nullable()->references('id')->on('anggota')->onDelete('cascade');
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
        Schema::dropIfExists('pembayaran');
    }
}
