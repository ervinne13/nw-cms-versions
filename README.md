# NuWorks CMS Model Version Control

This package enables tagging in Laravel models called versions. Versions allow different values at specified fields in a model.

## Installation

Require the package in your composer.json and update your dependencies:

````
$ composer require "ervinne/nw-cms-version:dev-develop"
````

Then add the service provider in your `config/app.php` providers array:

````
Ervinne\CMSVersion\Providers\CMSVersionServiceProvider::class,
````

## Register Commands

To allow the command for generating migrations out of models to be version controlled, add the `GenerateCMSVersionMigrations` class to the `App\Console\Kernel` `$commands` property:

````
   /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Ervinne\CMSVersion\Commands\GenerateCMSVersionMigrations::class
    ];
````

## Configurations

You can set which models to `"Version Control"` on the `config/cms-versions.php` configuration file. Publish the config to create this configuration file. Note that you will need to publish as well to generate the required javascript files when you want to reflect version data on an already displaying form (more on that later):

````
$ php artisan vendor:publish --provider="Ervinne\CMSVersion\Providers\CMSVersionServiceProvider"
````


<!--stackedit_data:
eyJoaXN0b3J5IjpbLTE3MDMwNTM0ODRdfQ==
-->