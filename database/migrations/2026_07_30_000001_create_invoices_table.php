<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();

            $table->string('company_name');
            $table->string('customer_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();

            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bsb')->nullable();
            $table->string('payment_method')->default('Bank Transfer');
            $table->string('payment_status')->default('Pending');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);

            // $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'generated'])->default('generated');
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('product');
            $table->text('description')->nullable();
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('tax', 5, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
