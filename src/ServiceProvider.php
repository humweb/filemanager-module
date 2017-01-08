<?php

namespace Humweb\Filemanager;

use Humweb\Filemanager\Repository\ConfigRepository;
use Humweb\Modules\ModuleBaseProvider;

class ServiceProvider extends ModuleBaseProvider
{

    protected $permissions = [

        // Users
        'filemanager.create' => [
            'name' => 'Create files',
            'description' => 'Create files.',
        ],
        'filemanager.edit' => [
            'name' => 'Edit files',
            'description' => 'Edit files.',
        ],
        'filemanager.list' => [
            'name' => 'List files',
            'description' => 'List files.',
        ],
        'filemanager.delete' => [
            'name' => 'Delete files',
            'description' => 'Delete files.',
        ],
    ];

    protected $moduleMeta = [
        'name' => 'Filemanager',
        'slug' => 'filemanager',
        'version' => '',
        'author' => '',
        'email' => '',
        'website' => '',
    ];

    public function boot()
    {
        $this->app['modules']->put('filemanager', $this);
        $this->loadLang();
        $this->loadViews();
        $this->publishViews();
    }


    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('filemanager.config', function ($app) {
            $c = $app['config']['filesystems'];

            return new ConfigRepository($c['disks'], $c['default'], $c['cloud']);
        });

        $this->app->bind('Humweb\Filemanager\Repository\ConfigRepositoryContract', 'filemanager.config');
    }
}
