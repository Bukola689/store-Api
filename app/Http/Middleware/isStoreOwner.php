<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class isStoreOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $storeId = $request->route('store');

        if (! $user = auth()->user()) {
            throw new NotFoundHttpException('user not found');
        }


        if(! $user->hasRole('store-owner')) {
            throw new AccessDeniedHttpException('User does not have the right role for access');
        }

        $exists = $user->stores()->where(function($query) use ($storeId, $user) {
           $query->where('owner_id', $user->id);

            if ($storeId) {
                $query->where('id', $storeId);
            }
       })->exists();

       if (! $exists) {
          throw new AccessDeniedHttpException('That store does not belong to you');

          //return response()->json('The store does not belong to you');
       }

        return $next($request);
    }
}
