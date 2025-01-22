<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (is_null($token)) {
            return response()->json(['message' => 'Treinador, faltou informar seu token'], 422);
        }

        if(is_null(AccessToken::where('token', $token)->first())) {
            return response()->json(['message' => 'Treinador, este token não é mais válido'], 401);
        }

        return $next($request);
    }
}
