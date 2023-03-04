<?php

namespace Tests\Unit\Model;


use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->loan = Loan::factory(['user_id' => $this->user])->create();
    }

    /**
     * Check loan can create in database
     * @return void
     */
    public function test_loan_can_create(): void
    {
        $this->assertTrue(true);
        $this->assertEquals(1, $this->loan->count());
        $this->assertEquals($this->user->id, $this->loan->user_id);
    }

    /**
     * Check loan have one user : belongs to
     * @return void
     */
    public function test_loan_belongs_to_user(): void
    {
        $this->assertEquals(1, $this->loan->user->count());
    }

    /**
     * Check created loan default status PENDING
     * @return void
     */
    public function test_loan_default_status_pending(): void
    {
        $this->assertEquals("PENDING", $this->loan->status);
    }

    /**
     * Check loan have hasmany relation with loan payments
     * @return void
     */
    public function test_loan_has_many_payments(): void
    {
        LoanPayment::factory(['loan_id' => $this->loan->id])->create();
        $this->assertEquals(1, $this->loan->loanPayments->count());
    }


}
