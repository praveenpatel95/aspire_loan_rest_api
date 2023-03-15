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
        $this->loan = Loan::factory()->create(['user_id' => $this->user]);
    }

    /**
     * Test loan can create in loan modal
     * @return void
     */
    public function test_loan_can_create(): void
    {
        $this->assertTrue(true);
        $this->assertEquals(1, $this->loan->count());
    }

    /**
     * Test loan is belong to user
     * @return void
     */
    public function test_loan_belongs_to_user(): void
    {
        $this->assertEquals($this->loan->user_id, $this->user->id);
    }

    /**
     * Test loan created time default status is PENDING
     * @return void
     */
    public function test_loan_default_status_pending(): void
    {
        $this->assertEquals("PENDING", $this->loan->status);
    }

    /**
     * Test loan have many payments
     * @return void
     */
    public function test_loan_has_many_payments(): void
    {
        LoanPayment::factory(2)->create(['loan_id' => $this->loan->id]);
        $this->assertEquals(2, $this->loan->loanPayments->count());
    }


}
