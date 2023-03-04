<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'scheduled_date',
        'payable_amount',
        'paid_amount',
        'status',
        'paid_date',
    ];

    function loan() :BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
