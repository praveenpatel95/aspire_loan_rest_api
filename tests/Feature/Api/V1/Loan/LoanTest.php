<?php

namespace Api\V1\Loan;

use App\Models\Loan;
use App\Models\User;
use Carbon\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test loan Request without fill the required field
     * @return void
     */
    public function test_loan_request_without_required_field() : void
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

    public function test_loan_request_with_required_field() : void
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
     * Test without login user not create the loan request
     * @return void
     */

    public function test_loan_request_without_login_customer() : void
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
     * Test can not get loan without login
     * @return void
     */
    public function test_get_loans_without_login() : void
    {

        $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/v1/admin/loan')
            ->assertUnauthorized() // Status code 401
            ->assertJson([
                'message' => "Unauthenticated."
            ]);
    }

    /**
     * Test except admin can not access all loans route
     * @return void
     */
    public function test_get_loans_without_login_as_a_admin() : void
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
     * Test Admin can get all customers loans
     * @return void
     */
    public function test_get_loans_with_login_as_a_admin() : void
    {

        $this->withHeaders($this->customerAuthorization('ADMIN'))
            ->get('/api/v1/admin/loan')
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true
            ]);
    }

    /**
     * Test admin can see loan detail by loan id
     * @return void
     */
    public function test_get_loan_detail_login_as_a_admin() : void
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);
        $this->withHeaders($this->customerAuthorization('ADMIN'))
            ->get('/api/v1/loan/' . $loan->id)
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true
            ]);
    }

    /**
     * Test customer can see only self loan detail
     * @return void
     */
    public function test_get_loan_detail_login_as_a_customer() : void
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);
        $this->withHeaders($this->customerAuthorization($user))
            ->get('/api/v1/loan/' . $loan->id)
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true
            ]);
    }

    /**
     * Test customer can not see other customer loan detail
     * @return void
     */
    public function test_get_loan_detail_other_customer_login_as_customer() : void
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);
        $this->withHeaders($this->customerAuthorization())
            ->get('/api/v1/loan/' . $loan->id)
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'You can not see this loan.'
            ]);
    }

    /**
     * Test loan only approve by admin
     * @return void
     */
    public function test_approve_loan_by_admin_only() : void
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);
        $this->withHeaders($this->customerAuthorization('ADMIN'))
            ->post("/api/v1/admin/loan/$loan->id/approve")
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => "APPROVED"
                ],
            ]);
    }

    /**
     * Test loan can not approve by except of the admin
     * @return void
     */
    public function test_not_approve_loan_login_as_customer() : void
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

    /**
     * Test Customer can get all self loan details
     * @return void
     */
    public function test_get_customer_loans_login_as_customer() : void
    {
        $user = User::factory()->hasLoans(5)->create();

        $response = $this->withHeaders($this->customerAuthorization('CUSTOMER', $user))
            ->get("/api/v1/customer/loan")
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true
            ]);
        $this->assertEquals(5, count($response['data']));
    }

    /**
     * Test if customer have no any loan
     * @return void
     */
    public function test_get_customer_have_no_any_loan_login_as_customer() : void
    {
        $user = User::factory()->create();

        $response = $this->withHeaders($this->customerAuthorization('CUSTOMER', $user))
            ->get("/api/v1/customer/loan")
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true
            ]);
        $this->assertEquals(0, count($response['data']));
    }

    /**
     * Common authorization service
     * @param $user
     * @param $role
     * @return string[]
     */
    public function customerAuthorization($role = 'CUSTOMER', $user = null) : array
    {
        $user = $user ? $user : User::factory()->create(['role' => $role]);
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
