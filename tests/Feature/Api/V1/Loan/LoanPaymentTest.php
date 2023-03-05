<?php

namespace Tests\Feature\Api\V1\Loan;

use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanPaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->hasLoans(1)->create();
    }

    public function test_make_loan_payment_without_fill_amount() : void
    {

        $loan = $this->user->loans[0];
        $this->withHeaders($this->customerAuthorization($this->user))
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


    public function test_make_loan_payment_for_unapproved_loan() : void
    {
        $loan = $this->user->loans[0];
        $this->withHeaders($this->customerAuthorization($this->user))
            ->post("/api/v1/customer/loan/$loan->id/payment", ['amount' => 1])
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => "Your loan is not approved yet."
            ]);
    }

    public function test_make_loan_payment_for_approved_loan() : void
    {
        $user = User::factory()->create();
        $loan = Loan::factory(['user_id' => $user->id, 'status' => 'APPROVED'])->create();
        LoanPayment::factory([
            'loan_id' => $loan->id,
            'payable_amount' => 3
        ])->create();

        $response = $this->withHeaders($this->customerAuthorization($user))
            ->post("/api/v1/customer/loan/$loan->id/payment", ['amount' => 3])
            ->assertSuccessful()
            ->assertJson([
                'success' => true,
            ]);
    }


    public function customerAuthorization($user) : array
    {
        $data = [
            'email' => $user->email,
            'password' => 'password'
        ];
        $response = $this->post('/api/v1/auth/login', $data)
            ->assertStatus(200);
        $token = $response['data']['token'];

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }
}
