<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notificacao;
use Illuminate\Support\Facades\Auth;

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
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user(); 
                $centro = $user->centro;
    
                $notificacoes = Notificacao::where('id_user', $user->id_user)
                    ->orderBy('data_envio', 'desc') 
                    ->take(5)
                    ->get();

                $naoLidas = Notificacao::where('id_user', $user->id_user)
                    ->where('lida', false)
                    ->count();
    
                $view->with(compact('notificacoes', 'naoLidas'));
            }
        });
    }
}
