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
        'author_name',
        'author_mail',
        'invoice_date',
        'due_date',
        'company_name',
        'customer_name',
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

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class)->orderByDesc('paid_at')->orderByDesc('id');
    }

    public function getTotalPaidAttribute(): float
    {
        return round((float) ($this->attributes['paid_amount'] ?? 0), 2);
    }

    public function getDueAmountAttribute(): float
    {
        return round((float) ($this->attributes['balance_due'] ?? 0), 2);
    }

    public function paymentStatusLabel(): string
    {
        $status = (string) ($this->payment_status ?: 'Due');

        if ($status === 'Due' && $this->due_date && $this->due_date->lt(now()->startOfDay()) && $this->due_amount > 0) {
            return 'Overdue';
        }

        return $status;
    }

    public function paymentStatusClass(): string
    {
        return match ($this->paymentStatusLabel()) {
            'Paid' => 'status-paid',
            'Partial' => 'status-partial',
            'Overdue' => 'status-overdue',
            default => 'status-due',
        };
    }

    public function paymentStatusBadgeClass(): string
    {
        return match ($this->paymentStatusLabel()) {
            'Paid' => 'badge-paid',
            'Partial' => 'badge-partial',
            default => 'badge-pending',
        };
    }

    public function recalculatePayments(): void
    {
        $totalPaid = round((float) $this->payments()->reorder()->sum('amount'), 2);
        $invoiceTotal = round((float) $this->total, 2);
        $dueAmount = round(max($invoiceTotal - $totalPaid, 0), 2);

        if ($invoiceTotal > 0 && $dueAmount <= 0) {
            $status = 'Paid';
        } elseif ($totalPaid > 0) {
            $status = 'Partial';
        } else {
            $status = 'Due';
        }

        $this->forceFill([
            'paid_amount' => $totalPaid,
            'balance_due' => $dueAmount,
            'payment_status' => $status,
        ])->save();
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
