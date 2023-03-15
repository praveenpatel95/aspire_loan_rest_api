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
     * Create user account
     * @param array $data
     * @param string $role
     * @return User
     */
    public function create(array $data, string $role): User
    {
        $data['role'] = $role;
        return $this->userRepository->create($data);
    }

    /**
     * Login user
     * @param array $data
     * @return User
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
