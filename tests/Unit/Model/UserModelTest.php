<?php

namespace Tests\Unit\Model;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['name' => 'prem']);
    }

    /**
     * Test user can create
     * @return void
     */
    public function test_user_can_create(): void
    {
        $this->assertEquals(1, $this->user->count());
    }

    /**
     * Test user has many loans
     * @return void
     */
    public function test_user_has_many_loans(): void
    {
        Loan::factory( 2)->create(['user_id' => $this->user->id]);
        $this->assertEquals(2, $this->user->loans->count());
    }
}
