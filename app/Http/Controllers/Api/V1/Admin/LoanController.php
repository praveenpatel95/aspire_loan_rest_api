<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Loan\LoanService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    use ApiResponse;
    protected LoanService $loanService;
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * get all loans
     * @return JsonResponse
     */
    public function get() : JsonResponse
    {
        return $this->success($this->loanService->get());
    }

    /**
     * Get loan detail by id
     * @param int $loanID
     * @return JsonResponse
     */
    public function getById(int $loanID) : JsonResponse
    {
        return $this->success($this->loanService->getById($loanID));
    }

    /**
     * Approve loan
     * @param int $loanID
     * @return JsonResponse
     */
    public function approve(int $loanID) : JsonResponse
    {
        return $this->success($this->loanService->approve($loanID));
    }
}
