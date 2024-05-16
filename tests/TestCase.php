<?php

namespace Mrkatz\QueryDetector\Tests;

use Monolog\Logger;
use Monolog\Handler\TestHandler;
use Illuminate\Database\Schema\Blueprint;
use Mrkatz\QueryDetector\Tests\Seeder\TestSeeder;
use Mrkatz\QueryDetector\QueryDetectorServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected static $setUpRun = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->withFactories(__DIR__ . '/Factories/');

        $this->seed(TestSeeder::class);
    }

    public function getPackageProviders($app)
    {
        return [
            QueryDetectorServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('querydetector.enabled', true);

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', 'base64:6Cu/ozj4gPtIjmXjr8EdVnGFNsdRqZfHfVjQkmTlg4Y=');
    }


    protected function setUpDatabase()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('bio');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id');
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id');
            $table->date('birthday');
            $table->string('city');
            $table->string('state');
            $table->string('website');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('body');
            $table->morphs('commentable');
        });
    }
}
