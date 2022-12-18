<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
  public function handle($request, Closure $next, ...$guards)
  {
    if (Auth::check()) {
      return $next($request);
    }
    return response()->json([
      'success' => false,
      'message' => 'unauthorized',
    ], Response::HTTP_UNAUTHORIZED);
  }
}
