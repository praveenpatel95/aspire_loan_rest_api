<?php

namespace Tests\Feature\Api\V1\Loan;

use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LoanPaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->hasLoans(1)->create();
    }

    /**
     * Test can not make payment without fill the amount
     * @return void
     */
    public function test_make_loan_payment_without_fill_amount(): void
    {

        $user = $this->user;
        $loan = $user->loans[0];
        Passport::actingAs($user);
        $user = $user->withToken();
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->token,
            'Accept' => 'application/json',
        ])
            ->post("/api/v1/customer/loan/$loan->id/payment")
            ->assertUnprocessable() // Status code 422
            ->assertJson([
                'success' => false,
                'message' => [
                    "amount" => [
                        "The amount field is required."
                    ]
                ]
            ]);
    }


    /**
     * Test customer can not make payment for unapproved loan
     * @return void
     */
    public function test_make_loan_payment_for_unapproved_loan(): void
    {
        $user = $this->user;
        $loan = $user->loans[0];
        Passport::actingAs($user);
        $user = $user->withToken();
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->token,
            'Accept' => 'application/json',
        ])
            ->post("/api/v1/customer/loan/$loan->id/payment", ['amount' => 1])
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => "Your loan is not approved yet."
            ]);
    }

    /**
     * Test customer can make payment for approved loan
     * @return void
     */
    public function test_make_loan_payment_for_approved_loan(): void
    {
        $user = $this->user;
        $loan = Loan::factory(['user_id' => $user->id, 'status' => 'APPROVED'])->create();
        LoanPayment::factory([
            'loan_id' => $loan->id,
            'payable_amount' => 3
        ])->create();

        Passport::actingAs($user);
        $user = $user->withToken();
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->token,
            'Accept' => 'application/json',
        ])
            ->post("/api/v1/customer/loan/$loan->id/payment", ['amount' => 3])
            ->assertSuccessful()
            ->assertJson([
                'success' => true,
            ]);
    }

}
