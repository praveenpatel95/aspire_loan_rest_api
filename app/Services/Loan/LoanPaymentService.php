<?php

namespace App\Services\Loan;

use App\Exceptions\BadRequestException;
use App\Repository\Loan\LoanInterface;
use App\Repository\Loan\LoanPaymentInterface;
use Illuminate\Support\Facades\Auth;
use Exception;

class LoanPaymentService
{
    protected LoanPaymentInterface $loanPaymentRepository;
    protected LoanInterface $loanRepository;

    public function __construct(
        LoanPaymentInterface $loanPaymentRepository,
        LoanInterface        $loanRepository
    )
    {
        $this->loanPaymentRepository = $loanPaymentRepository;
        $this->loanRepository = $loanRepository;
    }

    /**
     * Loan payment
     * @param int $loanId
     * @param float $amount
     * @return void
     * @throws BadRequestException
     */
    public function payment(int $loanId, float $amount): void
    {
        try {
            $userId = Auth::id();
            $loan = $this->loanRepository->getById($loanId);
            if ($loan->status != "APPROVED") {
                throw new BadRequestException("Your loan is not approved yet.");
            };

            $pendingLoans = $this->loanPaymentRepository->getLoanPendingPayments($loanId, $userId);
            if (count($pendingLoans) === 0) {
                throw new BadRequestException("All payment done for this loan");
            }
            $pendingLoan = $pendingLoans[0];
            if ($pendingLoan->payable_amount > $amount) {
                throw new BadRequestException("You need to pay more than or equal to: $pendingLoan->payable_amount");
            }
            $updatePayment = [
                'paid_amount' => $amount,
                'paid_date' => date('Y-m-d'),
                'status' => "PAID",
            ];
            $this->loanPaymentRepository->update($updatePayment, $pendingLoan->id);
            $this->updateLoanPaid($loanId, $userId);
        } catch (Exception $exception) {
            throw new BadRequestException($exception->getMessage());
        }
    }

    /**
     * Update Loan status as Paid if all payment done against to that loan
     * @param $loanId
     * @param $userId
     * @return bool
     */
    public function updateLoanPaid($loanId, $userId) : bool
    {
        $pendingLoan = $this->loanPaymentRepository->getLoanPendingPayments($loanId, $userId);
        if (count($pendingLoan) === 0) {
            return $this->loanRepository->update(['status' => 'PAID'], $loanId);
        }
        return false;
    }
}
