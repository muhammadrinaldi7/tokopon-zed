<?php

namespace App\Listeners;

use App\Services\CartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;

class MergeGuestCartOnLogin
{
    /**
     * Handle the event.
     * Triggered on both Login and Registered events.
     */
    public function handle(Login $event): void
    {
        $sessionId = Session::getId();

        if ($sessionId && $event->user) {
            $cartService = app(CartService::class);
            $cartService->mergeGuestCart($sessionId, $event->user->id);
        }
    }
}
