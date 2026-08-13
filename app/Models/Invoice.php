<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_no',
        'author_mail',
        'invoice_date',
        'due_date',
        'reference_code',
        'company_name',
        'customer_name',
        'customer_code',
        'email',
        'phone',
        'address',
        'bank_name',
        'account_number',
        'bsb',
        'payment_method',
        'payment_status',
        'subtotal',
        'tax_total',
        'total',
        'paid_amount',
        'balance_due',
        // 'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'author_mail' => 'string',
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'balance_due' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateInvoiceNo(): string
    {

        return 'INV-' . now()->format('Y-His');
        // $year = now()->format('Y');
        // $last = self::whereYear('created_at', $year)->orderByDesc('id')->first();
        // $next = $last ? ((int) substr($last->invoice_no, -5)) + 1 : 1;

        // return 'INV-' . $year . '-' . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }
}
