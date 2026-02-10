<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::guard('admin')->user();

        // 1. Update the session in the database
        $sessionId = $request->session()->getId();
        if ($sessionId) {
            DB::table('sessions')
                ->where('id', $sessionId)
                ->update([
                    'user_id' => $user->id,
                    'last_activity' => time(),
                ]);
        }

        // 2. Update the last_activity in the users table for persistence
        // Using DB::table to avoid updating the 'updated_at' timestamp
        DB::table('users')
            ->where('id', $user->id)
            ->update(['last_activity' => now()]);

        // Set default guard to admin so Laravel session handler picks up the user_id
        config(['auth.defaults.guard' => 'admin']);

        return $next($request);
    }
}