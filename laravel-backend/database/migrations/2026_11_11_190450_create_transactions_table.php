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
            
            // Use standard integer to match books.id type
            $table->integer('book_id');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            // Member foreign key
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            
            $table->string('status', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
