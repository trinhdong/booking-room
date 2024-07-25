<?php
namespace App\Providers;

use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\SpaceRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Repositories\SpaceRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(SpaceRepositoryInterface::class, SpaceRepository::class);
    }
}
