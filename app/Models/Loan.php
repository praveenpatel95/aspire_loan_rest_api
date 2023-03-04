<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'term'
    ];

    function loanPayments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
