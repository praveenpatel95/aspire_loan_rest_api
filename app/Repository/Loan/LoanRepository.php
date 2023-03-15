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
     * @return Loan
     */
    public function create(array $data): Loan
    {
        return Loan::create($data);
    }

    /**
     * Get loans with loan payments
     * @param $userId
     * @return Collection
     * @throws BadRequestException
     */
    public function get($userId): Collection
    {
        try {
            $query = Loan::with(['user', 'loanPayments']);
            if ($userId) {
                $query->where('user_id', $userId);
            }
            return $query->get();
        } catch (Exception $exception) {
            throw new BadRequestException($exception->getMessage());
        }
    }

    /**
     * Get loan detail by loan id
     * @param int $loanID
     * @return Loan
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
     * @return Loan
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
     * Update loan detail
     * @param array $data
     * @param $loanId
     * @return bool
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
