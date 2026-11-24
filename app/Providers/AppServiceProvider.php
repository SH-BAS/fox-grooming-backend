<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        \Illuminate\Pagination\Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $timezone = request()->cookie('timezone', 'UTC');
            session(['timezone' => $timezone]);
            $view->with('timezone', $timezone);
        });

        View::composer('layouts.admin', function ($view) {
            $unreadAdminNotifications = 0;
            $admin = auth()->guard('admin')->user();

            if ($admin) {
                $unreadAdminNotifications = Notification::query()
                    ->where('admin_id', $admin->id)
                    ->where('is_read', false)
                    ->count();
            }

            $view->with('unreadAdminNotifications', $unreadAdminNotifications);
        });
    }
}
