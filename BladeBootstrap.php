<?php

namespace BuglerV\Blade;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\ViewServiceProvider;
use BuglerV\Blade\BladeFakeApplication;

class BladeBootstrap
{
	public static function boot()
	{
		$container = self::setupContainer();

		self::setupConfig($container);
		self::setupDependencies($container);
		self::setupBlade($container);
	}

	/**
	 * Создает контейнер, если его еще нет.
	 *
	 * @return Container $container
	 */
	protected static function setupContainer()
	{
		$container = app();

        $container->bindIf(Application::class,function(){
            return new BladeFakeApplication;
        });

		Facade::setFacadeApplication($container);

		return $container;
	}

	/**
	 * Прочие зависимости.
	 *
	 * @param Container $container
	 * @return void
	 */
	protected static function setupDependencies(Container $container)
	{
        $container->bindIf('files', function () {
            return new Filesystem;
        }, true);

        $container->bindIf('events', function () {
            return new Dispatcher;
        }, true);
	}

	/**
	 * Устанавливаем конфиг для Blade.
	 *
	 * @param Container $container
	 * @return void
	 */
	protected static function setupConfig(Container $container)
	{
		if($container->bound('config') && app('config')->has('view.paths')){
			return;
		}

        $container->bindIf('config', function () {
            return new \Illuminate\Config\Repository([
				'view.paths' => ['Views'],
				'view.compiled' => 'cache',
				'view.app' => 'App\\',
			]);
        }, true);
	}

	/**
	 * Регистрируем Blade компонент.
	 *
	 * @param Container $container
	 * @return void
	 */
	protected static function setupBlade(Container $container)
	{
        (new ViewServiceProvider($container))->register();

        $container->instance('blade',new Blade($container));

        $container->bindIf(FactoryContract::class,function($app){
            return $app->get('view');
        });
	}
}