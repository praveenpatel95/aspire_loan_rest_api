<?php

namespace App\Http\Middleware;

use App\Exceptions\ForbiddenException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle admin request
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws ForbiddenException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if($user && $user->role === 'ADMIN'){
            return $next($request);
        }

        throw new ForbiddenException();

    }
}
