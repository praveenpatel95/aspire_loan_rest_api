<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\CreatRequest;
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
     * Create loan request
     * @param CreatRequest $creatRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BadRequestException
     */
    public function create(CreatRequest $creatRequest){
        return $this->success($this->loanService->create($creatRequest->all()));
    }

    /**
     * Gte customer loans
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(){
        return $this->success($this->loanService->getCustomerLoans());
    }
}
