<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->dateTime('paid_at');
            $table->string('payment_method')->default('Bank Transfer');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        $now = now();

        foreach (DB::table('invoices')->where('paid_amount', '>', 0)->get() as $invoice) {
            DB::table('invoice_payments')->insert([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->paid_amount,
                'paid_at' => $invoice->invoice_date ?: $now,
                'payment_method' => $invoice->payment_method ?: 'Bank Transfer',
                'reference_number' => $invoice->reference_code,
                'notes' => 'Migrated from original invoice paid amount',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
