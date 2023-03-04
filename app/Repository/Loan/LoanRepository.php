<?php

namespace App\Repository\Loan;

use App\Exceptions\BadRequestException;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class LoanRepository implements LoanInterface
{

    /**
     * Create loan
     * @param array $data
     * @return Loan|null
     */
    public function create(array $data): Loan
    {
        return Loan::create($data);
    }

    /**
     * get all loans
     * @return Collection
     */
    public function get(): Collection
    {
        return Loan::with(['user', 'loanPayments'])->get();
    }

    /**
     * get loan detail by id
     * @param int $loanID
     * @return Loan|null
     * @throws BadRequestException
     */
    public function getById(int $loanID): Loan
    {
        try {
            return Loan::with(['user', 'loanPayments'])->findOrFail($loanID);
        } catch (Exception $exception) {
            throw new BadRequestException("No loan was found by id.");
        }

    }

    /**
     * Approve loan
     * @param int $loanID
     * @return Loan|null
     * @throws BadRequestException
     */
    public function approve(int $loanID): Loan
    {
        try {
            $loan = Loan::findOrFail($loanID);
            $loan->status = "APPROVED";
            $loan->save();
            return $loan;
        } catch (Exception $exception) {
            throw new BadRequestException("No loan was found by id.");
        }
    }

    /**
     * get customer loans
     * @param int $userId
     * @return Collection|null
     * @throws BadRequestException
     */
    public function getCustomerLoans(int $userId): Collection
    {
        try {
            return Loan::with(['user', 'loanPayments'])
                ->where('user_id', $userId)
                ->get();
        } catch (Exception $exception) {
            throw new BadRequestException($exception->getMessage());
        }
    }

    /**
     * Update loan detail
     * @param array $data
     * @param $loanId
     * @return bool|null
     * @throws BadRequestException
     */
    public function update(array $data, $loanId): bool
    {
        try {
            return Loan::findOrFail($loanId)->update($data);
        } catch (Exception $exception) {
            throw new BadRequestException($exception->getMessage());
        }
    }
}
