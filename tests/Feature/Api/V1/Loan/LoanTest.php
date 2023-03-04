<?php

namespace Api\V1\Loan;

use App\Models\Loan;
use App\Models\User;
use Carbon\Factory;
use Tests\TestCase;

class LoanTest extends TestCase
{
    /**
     * Test loan Request without fill the required field
     * @return void
     */
    public function test_loan_request_without_required_field()
    {

        $this->withHeaders($this->customerAuthorization())
            ->post('/api/v1/customer/loan', [])
            ->assertUnprocessable() // Status code 422
            ->assertJson([
                'success' => false,
                'message' => [
                    "amount" => [
                        "The amount field is required."
                    ],
                    "term" => [
                        "The term field is required."
                    ]
                ]

            ]);
    }

    /**
     * Test Loan request filled the all the fields and create
     * @return void
     */

    public function test_loan_request_with_required_field()
    {
        $data = [
          'amount' => 10.000,
          'term' => 3,
        ];
        $this->withHeaders($this->customerAuthorization())
            ->post('/api/v1/customer/loan', $data)
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true
            ]);
    }

    /**
     * Check without login user not create the loan request
     * @return void
     */

    public function test_loan_request_without_login_customer()
    {
        $data = [
          'amount' => 10.000,
          'term' => 3,
        ];
        $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v1/customer/loan', $data)
            ->assertUnauthorized() // Status code 401
            ->assertJson([
                'message' => "Unauthenticated."
            ]);
    }

    /**
     * Check if it is not admin
     * @return void
     */
    public function test_get_loans_without_login()
    {

        $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/v1/admin/loan')
            ->assertUnauthorized() // Status code 401
            ->assertJson([
                'message' => "Unauthenticated."
            ]);
    }

    /**
     * Customer can't access the all loans
     * @return void
     */
    public function test_get_loans_without_login_as_a_admin()
    {

        $this->withHeaders($this->customerAuthorization())
            ->get('/api/v1/admin/loan')
            ->assertForbidden() // Status code 401
            ->assertJson([
                'success' => false,
                'message' => "You dont have access."
            ]);
    }

    /**
     * Admin can get all loans
     * @return void
     */
    public function test_get_loans_with_login_as_a_admin()
    {

        $this->withHeaders($this->customerAuthorization('ADMIN'))
            ->get('/api/v1/admin/loan')
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_get_loan_detail_login_as_a_admin()
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);
        $this->withHeaders($this->customerAuthorization('ADMIN'))
            ->get('/api/v1/admin/loan/'.$loan->id)
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_approve_loan_by_admin_only()
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);
        $this->withHeaders($this->customerAuthorization('ADMIN'))
            ->post("/api/v1/admin/loan/$loan->id/approve")
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true,
                'data' => [
                    'status'  => "APPROVED"
                ],
            ]);
    }

    public function test_not_approve_loan_login_as_customer()
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);
        $this->withHeaders($this->customerAuthorization())
            ->post("/api/v1/admin/loan/$loan->id/approve")
            ->assertForbidden() // Status code 200
            ->assertJson([
                'success' => false,
                'message' => "You dont have access."
            ]);
    }

    public function customerAuthorization($role='CUSTOMER')
    {
        $user = User::factory()->create(['role' => $role]);
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
