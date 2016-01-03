<?php

/*
 * This file is part of Alt Three Bugsnag.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Bugsnag;

use Bugsnag_Client as Bugsnag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the bugsnag service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class BugsnagServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig($this->app);
    }

    /**
     * Setup the config.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function setupConfig(Application $app)
    {
        $source = realpath(__DIR__.'/../config/bugsnag.php');

        if ($app instanceof LaravelApplication && $app->runningInConsole()) {
            $this->publishes([$source => config_path('bugsnag.php')]);
        } elseif ($app instanceof LumenApplication) {
            $app->configure('bugsnag');
        }

        $this->mergeConfigFrom($source, 'bugsnag');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBugsnag();
        $this->registerLogger();
    }

    /**
     * Register the bugsnag class.
     *
     * @return void
     */
    protected function registerBugsnag()
    {
        $this->app->singleton('bugsnag', function (Application $app) {
            $bugsnag = new Bugsnag($app->config->get('bugsnag.key'));

            $bugsnag->setStripPath($app['path.base']);
            $bugsnag->setProjectRoot($app['path']);
            $bugsnag->setAutoNotify(false);
            $bugsnag->setBatchSending(false);
            $bugsnag->setReleaseStage($app->environment());

            return $bugsnag;
        });

        $this->app->alias('bugsnag', Bugsnag::class);
    }

    /**
     * Register the logger class.
     *
     * @return void
     */
    protected function registerLogger()
    {
        $this->app->singleton('bugsnag.logger', function (Application $app) {
            $bugsnag = $app['bugsnag'];
            $user = function () use ($app) {
                if ($user = $app->auth->user()) {
                    return $user->toArray();
                }

                return [];
            };

            return new Logger($bugsnag, $user);
        });

        $this->app->alias('bugsnag.logger', Logger::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'bugsnag', 'bugsnag.logger',
        ];
    }
}
