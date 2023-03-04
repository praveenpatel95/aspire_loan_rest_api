<?php

namespace App\Providers;

use App\Repository\Auth\UserInterface;
use App\Repository\Auth\UserRepository;
use App\Repository\Loan\LoanInterface;
use App\Repository\Loan\LoanPaymentInterface;
use App\Repository\Loan\LoanPaymentRepository;
use App\Repository\Loan\LoanRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(LoanInterface::class, LoanRepository::class);
        $this->app->bind(LoanPaymentInterface::class, LoanPaymentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
