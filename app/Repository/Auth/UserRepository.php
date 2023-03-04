<?php

namespace App\Repository\Auth;

use App\Exceptions\BadRequestException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
class UserRepository implements UserInterface
{
    protected User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $data) :?User
    {
        //we can pass encryption from service but if in future change encryption method so use in repository
        try {
            $data['password'] = Hash::make($data['password']);
            return $this->user->create($data);
        }
        catch (Exception $exception){
            throw new BadRequestException($exception->getMessage());
        }
    }

    public function getByEmail(string $email) : ?User
    {
       return $this->user->where('email', $email)->first();
    }
}
