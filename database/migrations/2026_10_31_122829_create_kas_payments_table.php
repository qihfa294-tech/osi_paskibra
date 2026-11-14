<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kas_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('angkatan')->nullable(); // angkatan 26, 27
            $table->integer('amount')->default(0); // jumlah kas
            $table->enum('metode', ['cash','qris','transfer'])->default('cash');
            $table->string('bukti_foto')->nullable(); // <-- buat validasi foto
            $table->enum('status', ['pending','valid','tolak'])->default('pending'); // pending = nunggu PPL/Dev
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->text('catatan_validasi')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kas_payments');
    }
};