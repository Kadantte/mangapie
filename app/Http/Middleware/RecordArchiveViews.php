<?php

namespace App\Http\Middleware;

use App\Jobs\IncreaseArchiveHeat;
use App\Jobs\IncrementArchiveViews;
use Closure;

class RecordArchiveViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $archive = $request->route('archive');

            if (\Cache::tags(['config', 'views'])->get('enabled', true) == true)
                \Queue::push(new IncrementArchiveViews($user, $archive));

            if (\Cache::tags(['config', 'heat'])->get('enabled', false) == true)
                \Queue::push(new IncreaseArchiveHeat($user, $archive));
        }

        return $next($request);
    }
}
