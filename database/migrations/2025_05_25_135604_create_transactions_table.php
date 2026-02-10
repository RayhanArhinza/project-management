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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('membership_id')->constrained('memberships')->onDelete('cascade');
            $table->string('payment_proof')->nullable(); // Kolom untuk bukti pembayaran
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('transaction_code')->unique();
            $table->timestamps();
        });

        // Update tabel members untuk menambahkan kolom payment_proof
        Schema::table('members', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('membership_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });
    }
};
