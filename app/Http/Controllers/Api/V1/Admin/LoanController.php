<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Loan\LoanService;
use App\Traits\ApiResponse;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(){
        return $this->success($this->loanService->get());
    }

    /**
     * Get loan detail by id
     * @param int $loanID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById(int $loanID){
        return $this->success($this->loanService->getById($loanID));
    }

    /**
     * Approve loan
     * @param int $loanID
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(int $loanID){
        return $this->success($this->loanService->approve($loanID));
    }
}
