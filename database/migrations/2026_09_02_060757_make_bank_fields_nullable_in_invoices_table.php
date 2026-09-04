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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->change();
            $table->string('account_number')->nullable()->change();
            $table->string('bank_address')->nullable()->change();
            $table->string('payment_method')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('bank_name')->nullable(false)->change();
            $table->string('account_number')->nullable(false)->change();
            $table->string('bank_address')->nullable(false)->change();
            $table->string('payment_method')->nullable(false)->change();
        });
    }
};
