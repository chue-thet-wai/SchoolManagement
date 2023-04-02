<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Repositories\CreateInfoRepository;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Repositories\RegistrationRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CreateInfoRepositoryInterface::class, CreateInfoRepository::class);
        $this->app->bind(RegistrationRepositoryInterface::class, RegistrationRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
