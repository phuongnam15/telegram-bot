<?php

namespace App\Providers;

use App\Models\ScheduleGroupConfig;
use App\Repositories\ScheduleConfig\IScheduleConfigRepo;
use App\Repositories\ScheduleConfig\ScheduleConfigRepo;
use App\Repositories\ScheduleDeleteMessage\IScheduleDeleteMessageRepo;
use App\Repositories\ScheduleDeleteMessage\ScheduleDeleteMessageRepo;
use App\Repositories\ScheduleGroupConfig\IScheduleGroupConfigRepo;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IScheduleConfigRepo::class, ScheduleConfigRepo::class);
        $this->app->singleton(IScheduleGroupConfigRepo::class, ScheduleGroupConfig::class);
        $this->app->singleton(IScheduleDeleteMessageRepo::class, ScheduleDeleteMessageRepo::class);
    }
}