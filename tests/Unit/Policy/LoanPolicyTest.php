<?php

namespace Tests\Unit\Policy;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class LoanPolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test admin can view any customer loan detail
     * @return void
     */
    public function test_any_loan_can_view_by_admin(): void
    {
        $customer = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $customer->id]);
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $this->assertTrue($admin->can('view', $loan));
    }

    /**
     * Test admin can view any customer loan detail
     * @return void
     */
    public function test_customer_can_view_his_loan(): void
    {
        $customer = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $customer->id]);
        $this->assertTrue($customer->can('view', $loan));
    }

    /**
     * test Customer can not see other customer loan detail
     * @return void
     */
    public function test_customer_can_not_view_other_customer_loan(): void
    {
        $customer = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $customer->id]);

        $otherCustomer = User::factory()->create();
        $this->assertFalse($otherCustomer->can('view', $loan));
    }
}
