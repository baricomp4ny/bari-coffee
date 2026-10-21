<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Nomor WA untuk ditampilkan, mis. 6281234567890 -> 0812-3456-7890
        $number = preg_replace('/\D/', '', config('cafe.whatsapp'));
        $local = '0'.substr($number, 2);
        View::share('cafeWhatsapp', $number);
        View::share('cafeWhatsappDisplay', implode('-', [substr($local, 0, 4), substr($local, 4, 4), substr($local, 8)]));

        // Jumlah pesanan berstatus "baru" untuk badge di sidebar admin
        View::composer('layouts.admin', fn ($view) => $view->with('newOrderCount', Order::where('status', 'baru')->count()));
    }
}
