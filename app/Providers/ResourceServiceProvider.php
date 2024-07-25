<?php
namespace App\Providers;

use App\Http\Resources\BookingResource;
use App\Interfaces\BookingResourceInterface;
use Illuminate\Support\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BookingResource::class);
    }
}
