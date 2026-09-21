<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id(); $table->string('quotation_number')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'converted'])->default('draft');
            $table->decimal('subtotal', 15, 2)->default(0); $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0); $table->date('valid_until')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('quotations'); }
};