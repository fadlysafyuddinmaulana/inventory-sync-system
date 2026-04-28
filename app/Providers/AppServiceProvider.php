<?php

namespace App\Providers;

use App\Database\SqlServerConnector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('db.connector.sqlsrv', static function () {
            return new SqlServerConnector();
        });
    }
}
