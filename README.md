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

Set which models you want to `version control` in the `config/cms-versions.php` 

````
    'models' => [
        [
            'table'                => 'my_table',
            'primary_key'          => 'id',
            'classpath'            => \App\User::class,
            'is_published_field'   => 'my_is_published_field',
            'is_published_value'   => true,
            'is_unpublished_value' => false,
            'is_multiple'          => true,
            'is_migrated'          => false,
            'has_local_field'      => true,
        ],
    ],
````

Where `table`, `primary_key`, `classpath` is the table, primary key, and class path of the model you want to version control. `is_published_field` is the name of the field you want to version control inside that model, ex: `is_published`, `is_active`, etc. `is_published_value` is the value that needs to be set if the field specified
<!--stackedit_data:
eyJoaXN0b3J5IjpbMjk5NjA5OTg2XX0=
-->