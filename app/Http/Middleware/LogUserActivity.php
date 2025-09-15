<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuditLogService;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip logging for certain routes
        if ($this->shouldSkipLogging($request)) {
            return $next($request);
        }

        $response = $next($request);

        // Log the action after the response is sent
        if ($request->user()) {
            $this->logAction($request, $response);
        }

        return $response;
    }

    /**
     * Determine if logging should be skipped for this request.
     */
    protected function shouldSkipLogging(Request $request): bool
    {
        $skipRoutes = [
            'audit-logs.*',
            'api.*',
            'live-feed.*',
            'health.*',
        ];

        foreach ($skipRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log the user action.
     */
    protected function logAction(Request $request, Response $response): void
    {
        $action = $this->getActionName($request);
        
        AuditLogService::log(
            $action,
            $request->user()->id,
            $request
        );
    }

    /**
     * Get action name from request.
     */
    protected function getActionName(Request $request): string
    {
        $route = $request->route();
        $action = $route->getActionName();
        
        // Extract controller and method
        if (strpos($action, '@') !== false) {
            [$controller, $method] = explode('@', $action);
            $controller = class_basename($controller);
            return "{$controller}@{$method}";
        }

        return $request->method() . ' ' . $request->path();
    }
}