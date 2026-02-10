<?php

namespace App\Providers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\ServiceProvider;

class ExceptionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Meng-overwrite exception handler untuk menangani AuthenticationException
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            function ($app) {
                return new class($app) extends \Illuminate\Foundation\Exceptions\Handler {
                    public function unauthenticated($request, AuthenticationException $exception)
                    {
                        // Mengarahkan ke /admin/login jika route mengarah ke admin
                        if ($request->is('admin') || $request->is('admin/*')) {
                            return redirect()->guest('/admin/login');
                        }

                        // Jika tidak, mengarahkan ke /login biasa
                        return redirect()->guest('/login');
                    }
                };
            }
        );
    }

    public function register()
    {
        // Tidak ada register tambahan yang perlu dilakukan di sini
    }
}


