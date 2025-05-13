<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notificacao;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Agendamento;
use App\Observers\AgendamentoObserver;
use App\Notifications\Channels\CustomDatabaseChannel;


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
                    ->where('lida', false)
                    ->orderBy('data_envio', 'desc') 
                    ->take(5)
                    ->get();

                $naoLidas = Notificacao::where('id_user', $user->id_user)
                    ->where('lida', false)
                    ->count();
    
                $view->with(compact('notificacoes', 'naoLidas'));
            }
        });
       $this->app->make(ChannelManager::class)
        ->extend('custom_database', function ($app) {
            return $app->make(CustomDatabaseChannel::class);
        });

        Agendamento::observe(AgendamentoObserver::class);
    }
}
