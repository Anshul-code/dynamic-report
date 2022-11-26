<?php

namespace AnshulNetgen\JasperReport;

use Illuminate\Support\ServiceProvider;

class JasperReportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/jasperreport.php',
            'jasper-report'
        );

        $this->publishes([
            __DIR__.'/config/jasperreport.php' => config_path('jasperreport.php'),
        ]);
    }
}
