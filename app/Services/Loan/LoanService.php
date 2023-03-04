<?php

namespace App\Services\Loan;

use App\Exceptions\BadRequestException;
use App\Models\Loan;
use App\Repository\Loan\LoanInterface;
use App\Repository\Loan\LoanPaymentInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class LoanService
{
    protected LoanInterface $loanRepository;
    protected LoanPaymentInterface $loanPaymentRepository;

    public function __construct(
        LoanInterface        $loanRepository,
        LoanPaymentInterface $loanPaymentRepository
    )
    {
        $this->loanRepository = $loanRepository;
        $this->loanPaymentRepository = $loanPaymentRepository;
    }

    /**
     * Handle Loan Request
     * @param array $data
     * @return Loan
     * @throws BadRequestException
     */

    public function create(array $data): Loan
    {
        $ewi = $this->calculateLoanEwi($data['amount'], $data['term']);
        $userId = Auth::id();
        DB::beginTransaction();
        try {
            $data['user_id'] = $userId;
            $loan = $this->loanRepository->create($data);

            //Create Loan Payment term basis
            for ($i = 0; $i <= $data['term']; $i++) {
                $scheduledDate = Carbon::now()->addDays(7 * $i)->format('Y-m-d');
                $paymentData = [
                    'loan_id' => $loan->id,
                    'payable_amount' => $ewi,
                    'scheduled_date' => $scheduledDate,
                ];
                $this->loanPaymentRepository->create($paymentData);
            }
            DB::commit();
            return $loan;

        } catch (Exception $exception) {
            DB::rollBack();
            throw new BadRequestException($exception->getMessage());
        }


    }

    /**
     * @param float $totalAmount
     * @param int $term
     * @return float
     */
    public function calculateLoanEwi(float $totalAmount, int $term): float
    {
        if ($term === 0)
            return 0;
        return round($totalAmount / $term, 5);
    }

    /**
     * Get all loans
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->loanRepository->get();
    }

    /**
     * Get loan detail by ID
     * @param int $loanID
     * @return Loan
     */
    public function getById(int $loanID): Loan
    {
        return $this->loanRepository->getById($loanID);
    }

    public function approve(int $loanID): Loan
    {
        return $this->loanRepository->approve($loanID);
    }

    public function getCustomerLoans(): Collection
    {
        $userId = Auth::id();
        return $this->loanRepository->getCustomerLoans($userId);
    }
}
