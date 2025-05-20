<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'delivery_date',
        'submit_date', 
        'total_amount',
        'user_id',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'submit_date' => 'datetime',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }
}