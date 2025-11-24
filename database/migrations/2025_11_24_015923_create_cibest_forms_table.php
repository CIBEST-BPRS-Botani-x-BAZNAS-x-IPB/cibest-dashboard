<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cibest_forms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('nama_enumerator');
            $table->date('waktu_pengambilan_data');

            // I. KARAKTERISTIK RESPONDEN
            $table->string('nama_responden');
            $table->string('nomor_kontak')->nullable();
            $table->text('alamat');
            // sementara string dulu untuk lokasi
            $table->string('provinsi');
            $table->string('kabupaten_kota');
            $table->string('kecamatan');
            $table->string('desa_kelurahan');
            $table->integer('usia');
            $table->foreignId('jenis_kelamin_option_id')->constrained();
            $table->foreignId('status_perkawinan_option_id')->constrained();
            $table->foreignId('pendidikan_formal_option_id')->constrained();
            $table->foreignId('pendidikan_nonformal_option_id')->constrained();
            $table->boolean('memiliki_usaha_sendiri');
            $table->bigInteger('rata_rata_profit');

            // II. INFORMASI BANTUAN ZISWAF YANG DIPEROLEH MUSTAHIK
            $table->foreignId('bantuan_ziswaf_section_id')->nullable()->unique()->constrained();

            // III. INFORMASI PEMBIAYAAN SYARIAH YANG DIPEROLEH RESPONDEN
            $table->foreignId('pembiayaan_syariah_section_id')->nullable()->unique()->constrained();

            // IV. KARAKTERISTIK RUMAH TANGGA
            // karakteristik_rumah_tangga_sections one to many

            // V. DIMENSI MATERIAL: PENDAPATAN DAN KETENAGAKERJAAN
            // pendapatan_ketenagakerjaan_sections one to many

            // VI. DIMENSI MATERIAL: PENGELUARAN RUMAH TANGGA
            // -- Pengeluaran mingguan
            $table->bigInteger('pangan')->default(0);
            $table->bigInteger('rokok_tembakau')->default(0);
            // -- Pengeluaran bulanan
            $table->bigInteger('sewa_rumah')->default(0);
            $table->bigInteger('listrik')->default(0);
            $table->bigInteger('air')->default(0);
            $table->bigInteger('bahan_bakar')->default(0);
            $table->bigInteger('sandang')->default(0);
            $table->bigInteger('pendidikan')->default(0);
            $table->bigInteger('kesehatan')->default(0);
            $table->bigInteger('transportasi')->default(0);
            $table->bigInteger('komunikasi')->default(0);
            $table->bigInteger('rekreasi_hiburan')->default(0);
            $table->bigInteger('perawatan_badan')->default(0);
            $table->bigInteger('sosial_keagamaan')->default(0);
            $table->bigInteger('angsuran_kredit')->default(0);
            $table->bigInteger('lain_lain')->default(0);

            // VII. DIMENSI MATERIAL: TABUNGAN DAN SIMPANAN
            $table->boolean('memiliki_tabungan_bank_konvensional');
            $table->boolean('memiliki_tabungan_bank_syariah');
            $table->boolean('memiliki_tabungan_koperasi_konvensional');
            $table->boolean('memiliki_tabungan_koperasi_syariah');
            $table->boolean('memiliki_tabungan_lembaga_zakat');
            $table->boolean('mengikuti_arisan_rutin');
            $table->boolean('memiliki_simpanan_rumah');

            // VIII. DIMENSI SPIRITUAL 
            $table->foreignId('shalat_sebelum')->constrained('keterangan_shalat_likert');
            $table->foreignId('puasa_sebelum')->constrained('keterangan_puasa_likert');
            $table->foreignId('zakat_infak_sebelum')->constrained('keterangan_zakat_infak_likert');
            $table->foreignId('lingkungan_keluarga_sebelum')->constrained('keterangan_lingkungan_keluarga_likert');
            $table->foreignId('kebijakan_pemerintah_sebelum')->constrained('keterangan_kebijakan_pemerintah_likert');

            $table->foreignId('shalat_setelah')->constrained('keterangan_shalat_likert');
            $table->foreignId('puasa_setelah')->constrained('keterangan_puasa_likert');
            $table->foreignId('zakat_infak_setelah')->constrained('keterangan_zakat_infak_likert');
            $table->foreignId('lingkungan_keluarga_setelah')->constrained('keterangan_lingkungan_keluarga_likert');
            $table->foreignId('kebijakan_pemerintah_setelah')->constrained('keterangan_kebijakan_pemerintah_likert');

            // IX PEMBINAAN DAN PENDAMPINGAN
            $table->foreignId('pembinaan_pendampingan_section_id')->nullable()->unique()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cibest_forms');
    }
};
