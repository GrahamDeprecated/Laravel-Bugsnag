<?php

/*
 * This file is part of Alt Three Bugsnag.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Tests\Bugsnag;

use AltThree\Bugsnag\BugsnagServiceProvider;
use AltThree\Bugsnag\Logger;
use Bugsnag_Client as Bugsnag;
use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ServiceProviderTest extends AbstractPackageTestCase
{
    use ServiceProviderTrait;

    protected function getServiceProviderClass($app)
    {
        return BugsnagServiceProvider::class;
    }

    public function testRepositoryFactoryIsInjectable()
    {
        $this->app->config->set('bugsnag.key', 'qwertyuiop');

        $this->assertIsInjectable(Bugsnag::class);
    }

    public function testLoggerIsInjectable()
    {
        $this->app->config->set('bugsnag.key', 'qwertyuiop');

        $this->assertIsInjectable(Logger::class);
    }
}
