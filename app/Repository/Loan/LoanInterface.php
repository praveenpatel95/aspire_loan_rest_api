<?php

namespace App\Repository\Loan;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Collection;

interface LoanInterface
{
    public function create(array $data): ?Loan;

    public function get(): Collection;

    public function getById(int $loanID): ?Loan;

    public function approve(int $loanID): ?Loan;

    public function getCustomerLoans(int $userId): ?Collection;

    public function update(array $data, $loanId) :?bool;
}
