<?php

namespace App\Http\Middleware;

use App\Models\AllowedIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to restrict access to the application based on the IP address.
 */
class RestrictIpAddress
{
    /**
     * Handles an incoming request.
     *
     * @param  Request  $request
     *   The incoming request.
     * @param  Closure  $next
     *   The next middleware in the pipeline.
     *
     * @return Response
     *   The response from the middleware.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = AllowedIp::pluck('ip_address')->toArray();

        if (!empty($allowedIps) && !IpUtils::checkIp($request->ip(), $allowedIps)) {
            return response()->json(['message' => 'Access denied from your IP address. Your IP is: ' . $request->ip()], 403);
        }

        return $next($request);
    }
}
