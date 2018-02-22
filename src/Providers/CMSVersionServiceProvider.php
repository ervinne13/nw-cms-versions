<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ervinne\CMSVersion\Providers;

use Illuminate\Support\ServiceProvider;
use Ervinne\CMSVersion\Repositories\CMSVersionRepository;
use Ervinne\CMSVersion\Repositories\Impl\CMSVersionRepositoryDefaultImpl;
use Ervinne\CMSVersion\Services\CMSVersionConfig;
use Ervinne\CMSVersion\Services\Impl\CMSVersionConfigLaravelImpl;
use function config_path;
use function public_path;

/**
 * Description of CMSVersionServiceProvider
 *
 * @author Ervinne Sodusta <ervinne.sodusta@nuworks.ph>
 */
class CMSVersionServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../publish/config/cms-version.php'                   => config_path('cms-version.php'),
            __DIR__ . '/../publish/public/vendor/cms-version/cms-version.js' => public_path('vendor/cms-version/cms-version.js'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../publish/migrations/create_cms_versions_table.php');
        
        //  routes
        require __DIR__ . '/../routes/nw-cms-version.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //  Services
        $this->app->bind(CMSVersionConfig::class, CMSVersionConfigLaravelImpl::class);

        //  Repositories
        $this->app->bind(CMSVersionRepository::class, CMSVersionRepositoryDefaultImpl::class);
    }

}
