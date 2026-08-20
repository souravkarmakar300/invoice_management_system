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
            $table->string('author_name')->nullable()->after('author_mail');

        $table->dropColumn([
            'reference_code',
            'customer_code',
        ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('author_name')->nullable(false)->change();
            $table->string('author_mail')->nullable(false)->change();
        });
    }
};
