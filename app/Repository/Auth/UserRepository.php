<?php

namespace App\Repository\Auth;

use App\Exceptions\BadRequestException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
class UserRepository implements UserInterface
{
    public function create(array $data) :User
    {
        //we can pass encryption from service but if in future change encryption method so use in repository
        try {
            $data['password'] = Hash::make($data['password']);
            return User::create($data);
        }
        catch (Exception $exception){
            throw new BadRequestException($exception->getMessage());
        }
    }

    public function getByEmail(string $email) : ?User
    {
       return User::where('email', $email)->first();
    }
}
