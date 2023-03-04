<?php

namespace App\Repository\Loan;

use App\Models\LoanPayment;
use Illuminate\Database\Eloquent\Collection;

interface LoanPaymentInterface
{
    public function create(array $data) :?LoanPayment;

    public function getPendingLoans(int $loanId, int $userId) :?Collection;
    public function update(array $data, int $updateId) :?bool;
}
