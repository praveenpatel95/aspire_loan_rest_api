<?php

namespace App\Repository\Loan;

use App\Exceptions\BadRequestException;
use App\Models\Loan;
use App\Models\LoanPayment;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class LoanPaymentRepository implements LoanPaymentInterface
{
    /**
     * Create Loan Payment
     * @param array $data
     * @return LoanPayment|null
     */
    public function create(array $data): ?LoanPayment
    {
        return LoanPayment::create($data);
    }

    /**
     * get all pending loan payemnts of the customers
     * @param int $loanId
     * @param int $userId
     * @return Collection|null
     * @throws BadRequestException
     */
    public function getPendingLoans(int $loanId, int $userId) :?Collection
    {
        try {
            return LoanPayment::where('status', 'PENDING')
                ->where('loan_id', $loanId)
                ->get();
        }
        catch (Exception $exception){
            throw new BadRequestException('No any pending loan payment found');
        }

    }

    /**
     * Update loan payment detail
     * @param array $data
     * @param int $updateId
     * @return bool|null
     * @throws BadRequestException
     */
    public function update(array $data, int $updateId) :?bool
    {
        try {
            return LoanPayment::findOrFail($updateId)->update($data);
        }
        catch (Exception $exception){
            throw new BadRequestException($exception->getMessage());
        }
    }
}
