# Alt Three Bugsnag

A Bugsnag bridge for Laravel 5.


## Installation

[PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.6+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Alt Three Bugsnag, simply add the following line to the require block of your `composer.json` file:

```
"alt-three/bugsnag": "~1.1"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Alt Three Bugsnag is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'AltThree\Bugsnag\BugsnagServiceProvider'`


## Security

If you discover a security vulnerability within this package, please e-mail us at support@alt-three.com. All security vulnerabilities will be promptly addressed.


## License

Alt Three Bugsnag is licensed under [The MIT License (MIT)](LICENSE).
