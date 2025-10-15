<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log for authenticated users and specific methods
        if (Auth::check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $request->method(),
                'model' => $this->extractModelName($request->route()->getName()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }

    private function extractModelName($routeName)
    {
        if (!$routeName) return null;
        
        $parts = explode('.', $routeName);
        return isset($parts[1]) ? ucfirst($parts[1]) : null;
    }
}