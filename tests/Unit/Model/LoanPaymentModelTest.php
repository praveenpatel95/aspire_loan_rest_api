<?php

namespace Tests\Unit\Model;

use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanPaymentModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->loan = Loan::factory()->create(['user_id' => $this->user->id]);
        $this->loanPayments = LoanPayment::factory()->create(['loan_id' => $this->loan->id]);
    }

    /**
     * Test loan payment can create
     * @return void
     */
    public function test_loan_payment_create() : void
    {
        $this->assertEquals(1, $this->loanPayments->count());
    }

    /**
     * Test loan payment belongs to loan
     * @return void
     */
    public function test_loan_payment_belongs_to_loan() : void
    {
        $this->assertEquals($this->loan->id, $this->loanPayments->loan_id);
    }
}
