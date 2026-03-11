<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProjectSelected
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && !$request->user()->current_project_id) {
            return redirect()->route('projects.index');
        }

        return $next($request);
    }
}
