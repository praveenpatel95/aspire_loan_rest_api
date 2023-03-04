<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\LoanPaymentRequest;
use App\Services\Loan\LoanPaymentService;
use App\Traits\ApiResponse;

class LoanPaymentController extends Controller
{
    use ApiResponse;
    protected LoanPaymentService $loanPaymentService;
    public function __construct(LoanPaymentService $loanPaymentService)
    {
        $this->loanPaymentService = $loanPaymentService;
    }

    /**
     * Customer payment request
     * @param int $loanId
     * @param LoanPaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BadRequestException
     */
    public function payment(int $loanId, LoanPaymentRequest $request)
    {
        return $this->success($this->loanPaymentService->payment($loanId, $request->input('amount')));
    }
}
