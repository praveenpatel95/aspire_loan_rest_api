<?php

namespace App\Services\Loan;

use App\Exceptions\BadRequestException;
use App\Models\Loan;
use App\Repository\Loan\LoanInterface;
use App\Repository\Loan\LoanPaymentInterface;
use Carbon\Carbon;
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
     * @return void
     * @throws BadRequestException
     * use DB::beginTransaction() if in case process not completed.
     */

    public function create(array $data) : ?Loan
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
     * @param $totalAmount
     * @param $term
     * @return float|int
     */
    public function calculateLoanEwi($totalAmount, $term)  :float
    {
        if ($term === 0)
            return 0;
        return round($totalAmount / $term, 5);
    }

    /**
     * Get loans from repository
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(){
        return $this->loanRepository->get();
    }

    public function getById(int $loanID){
        return $this->loanRepository->getById($loanID);
    }

    public function approve(int $loanID){
        return $this->loanRepository->approve($loanID);
    }

    public function getCustomerLoans(){
        $userId = Auth::id();
        return $this->loanRepository->getCustomerLoans($userId);
    }
}
