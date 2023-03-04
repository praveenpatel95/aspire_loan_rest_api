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
        $this->user = User::factory(['name' => 'prem'])->create();
    }

    /**
     * User can create
     * @return void
     */
    public function test_user_can_create(): void
    {
        $this->assertEquals('prem', $this->user->name);

    }

    /**
     * User have loans : User has many relation with loans
     * @return void
     */
    public function test_user_has_many_loans(): void
    {
        Loan::factory(['user_id' => $this->user->id])->create();
        $this->assertEquals(1, $this->user->loans->count());
    }
}
