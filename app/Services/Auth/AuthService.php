<?php

namespace App\Services\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Repository\Auth\UserInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected UserInterface $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * handle create account request
     * @param array $data
     * @param string $role
     * @return \App\Models\User|null
     */
    public function create(array $data, string $role): User
    {
        $data['role'] = $role;
        return $this->userRepository->create($data);
    }

    /**
     * handle login request
     * @param array $data
     * @return \App\Models\User|\Illuminate\Database\Eloquent\Model
     * @throws InvalidCredentialsException
     */
    public function login(array $data): User
    {
        $user = $this->userRepository->getByEmail($data['email']);
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new InvalidCredentialsException('These credentials do not match our records.');
        }
        return $user->withToken();
    }
}
