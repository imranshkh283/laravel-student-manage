<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\questions_session as QuestionsSession;

class ExamSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Prevent redirect loop
        // dd($request->routeIs('quiz_page'), 'middleware');
        if (!$request->routeIs('quiz_page')) {
            $examSession = QuestionsSession::where('user_id', $request->user()->id)
                ->where('status', 'pending')
                ->whereNull('completed_at')
                ->orderBy('id', 'desc')
                ->first();
            if ($examSession) {
                return redirect()->route('quiz_page');
            }
        }

        return $next($request);
    }
}
