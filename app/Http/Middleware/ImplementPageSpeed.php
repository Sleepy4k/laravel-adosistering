<?php

namespace App\Http\Middleware;

use App\Facades\PageSpeed;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImplementPageSpeed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!PageSpeed::shouldProcessPageSpeed($request, $response)) {
            return $response;
        }

        $content = $response->getContent();
        $parsedContent = PageSpeed::parseContent($content);

        return $response->setContent($parsedContent);
    }
}
