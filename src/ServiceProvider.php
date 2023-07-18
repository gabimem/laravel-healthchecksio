<?php


namespace Gabimem\LaravelHealthchecksIO;


use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Stringable;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/healthchecks.php' => config_path('healthchecks.php'),
        ]);

        Event::macro('healthchecks', function (string $jobName) {
            /* @var Event $this */
            return $this
                ->pingBefore(Healthchecks::getPingUrl($jobName, Healthchecks::COMMAND_START))
                ->onSuccess(fn(Stringable $output) => Healthchecks::ping($jobName, Healthchecks::COMMAND_SUCCESS, $output))
                ->onFailure(fn(Stringable $output) => Healthchecks::ping($jobName, Healthchecks::COMMAND_FAILURE, $output));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/healthchecks.php', 'healthchecks'
        );
    }
}
