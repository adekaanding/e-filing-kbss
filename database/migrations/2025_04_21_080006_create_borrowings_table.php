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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->string('borrower_name');
            $table->date('borrow_date');
            $table->date('return_date')->nullable();
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Dalam Pinjaman', 'Dikembalikan', 'Belum Dikembalikan'])->default('Dalam Pinjaman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
