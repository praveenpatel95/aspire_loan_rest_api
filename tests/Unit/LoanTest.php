<?php

namespace Tests\Unit;

use App\Repository\Loan\LoanPaymentRepository;
use App\Repository\Loan\LoanRepository;
use App\Services\Loan\LoanService;
use PHPUnit\Framework\TestCase;

class LoanTest extends TestCase
{
    protected $loanService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanService = new LoanService(new LoanRepository(), new LoanPaymentRepository());
    }

    /**
     * Calculate Estimated Weekly installment amount
     * @return void
     */
    public function test_loan_ewi_with_round_number(): void
    {
        $response = $this->loanService->calculateLoanEwi(10.000, 3);
        $this->assertEquals(3.33333, $response);
    }


    public function test_loan_ewi_with_exact_number(): void
    {
        $response = $this->loanService->calculateLoanEwi(99, 3);
        $this->assertEquals(33, $response);
    }

    public function test_loan_ewi_with_total_amount_zero(): void
    {
        $response = $this->loanService->calculateLoanEwi(0, 3);
        $this->assertEquals(0, $response);
    }

    public function test_loan_ewi_with_term_zero(): void
    {
        $response = $this->loanService->calculateLoanEwi(0, 0);
        $this->assertEquals(0, $response);
    }


}
