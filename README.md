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
        ],
    ],
````

Where `table`, `primary_key`, `classpath` is the table, primary key, and class path of the model you want to version control. `is_published_field` is the name of the field you want to version control inside that model, ex: `is_published`, `is_active`, etc. `is_published_value` is the value that needs to be set to consider the field as "published", `is_unpublished_value` is vice versa.

In case you want to version control multiple fields in 1 model, just duplicate the model entries in the `models` array with the same `table`, `primary_key`, and `classpath`.

## Migrating the Models

To enable version control on the models, the user must `migrate` after configuration with the following command:

````
$ php artisan make:cmsvmigration
````

This will create a migration called `create_cms_version_pivot_tables` that will contain database changes based on the configuration in `config/cms-versions.php`. Migrate afterwards using the command:

````
$ php artisan migrate
````

## Model Setup

Use the trait `VersionControlled` in your model to enable versioning functionalities:

````
...
use Ervinne\CMSVersion\VersionControlled;

class BannerSet extends Model
{
    use VersionControlled;
...
````

## Saving Model to a Version

Before you save a model, you need to save a version to save it to first:

````
CMSVersion::insert([
    'display_name' => 'Default',
    'description' => 'Default Version',
    'status' => 'Published'
]);
````

Save your model by specifying the id of the saved `CMSVersion` object in the `

## Version Status

Version `status` can be any string you want but `"Published"` is reserved and is set to signify that the version is the currently used version. If you try to save version controlled fields to a model set to a version that's not `published` then those changes wont save on the model itself but will be put in the version records only. If a version with model data is set to `published` it's data will be updated to the respective models.
<!--stackedit_data:
eyJoaXN0b3J5IjpbLTE0MDkzMjM4NzRdfQ==
-->